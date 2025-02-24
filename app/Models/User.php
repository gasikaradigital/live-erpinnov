<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Laravel\Jetstream\HasProfilePhoto;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Paddle\Billable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use SoftDeletes;
    use Billable;
    protected $with = ['profile'];
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'email',
        'password',
        'email_verified_at',
        'is_active',
        'google_id',
        'otp',
        'otp_expires_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean'
        ];
    }

    public function activePlan()
    {
        return $this->subscriptions()
            ->whereIn('status', [Subscription::STATUS_ACTIVE, Subscription::STATUS_TRIAL])
            ->where(function ($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>', now());
            })
            ->with('plan') // Inclure le plan associé
            ->latest()
            ->first()
            ->plan ?? null;
    }


    public function entreprises()
    {
        return $this->hasMany(Entreprise::class);
    }

    public function instances()
    {
        return $this->hasMany(Instance::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where(function($query) {
                $query->where('status', 'active')
                      ->orWhere('status', 'trial');
            })
            ->whereDate('end_date', '>=', now())
            ->first();
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function canCreateInstance()
    {
        // Récupérer l'abonnement actif (trial ou payant)
        $activeSubscription = $this->subscriptions()
            ->whereIn('status', ['active', 'trial'])
            ->latest()
            ->first();

        if (!$activeSubscription) {
            return false;
        }

        // Compter les instances selon le type d'abonnement
        $instanceCount = $this->instances()
            ->whereHas('subscription', function($query) use ($activeSubscription) {
                $query->where('id', $activeSubscription->id);
            })->count();

        // Si c'est un abonnement payant, vérifier la limite du plan
        if ($activeSubscription->status === 'active') {
            return $instanceCount < ($activeSubscription->plan->instance_limit ?? 1);
        }

        // Si c'est un essai, limiter à 1 instance
        if ($activeSubscription->status === 'trial') {
            return $instanceCount < 1;
        }

        return false;
    }

    public function remainingInstances()
    {
        $activeSubscription = $this->subscriptions()
            ->whereIn('status', ['active', 'trial'])
            ->latest()
            ->first();

        if (!$activeSubscription) {
            return 0;
        }

        $instanceCount = $this->instances()
            ->whereHas('subscription', function($query) use ($activeSubscription) {
                $query->where('id', $activeSubscription->id);
            })->count();

        if ($activeSubscription->status === 'active') {
            return max(0, ($activeSubscription->plan->instance_limit ?? 1) - $instanceCount);
        }

        return $activeSubscription->status === 'trial' ? max(0, 1 - $instanceCount) : 0;
    }


    public function getInitialsAttribute()
    {
        $names = explode(' ', $this->name);
        $initials = '';

        if (count($names) >= 2) {
            // Prendre la première lettre du premier et du dernier mot
            $initials = strtoupper(substr($names[0], 0, 1) . substr($names[count($names)-1], 0, 1));
        } else {
            // Si un seul mot, prendre les deux premières lettres
            $initials = strtoupper(substr($this->name, 0, 2));
        }

        return $initials;
    }

    public function hasUsedFreeTrial()
    {
        return $this->instances()
            ->whereHas('subscription', function($query) {
                $query->whereHas('plan', function($q) {
                    $q->where('is_free', true);
                });
            })
            ->exists();
    }

    public function hasReachedTrialLimit()
    {
        return $this->instances()
            ->whereHas('subscription', function($query) {
                $query->where('status', Subscription::STATUS_TRIAL);
            })
            ->count() >= 1;
    }

}
