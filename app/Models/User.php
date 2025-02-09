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

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use SoftDeletes;
    use Billable;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'is_active',
        'google_id'
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
    // protected static function booted()
    // {
    //     static::created(function ($user) {
    //         $freePlan = Plan::where('is_free', true)->where('is_default', true)->first();
    //         if ($freePlan) {
    //             Subscription::create([
    //                 'user_id' => $user->id,
    //                 'plan_id' => $freePlan->id,
    //                 'start_date' => now(),
    //                 'end_date' => now()->addDays($freePlan->duration_days),
    //                 'status' => 'active'
    //             ]);
    //         }
    //     });
    // }


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

    public function activePlan()
    {
        return $this->activeSubscription()?->plan;
    }

    public function canCreateInstance()
    {
        $activePlan = $this->activePlan();
        if (!$activePlan) {
            return false;
        }
        return $activePlan->instance_limit === null || $this->instances()->count() < $activePlan->instance_limit;
    }

    public function remainingInstances()
    {
        $activePlan = $this->activePlan();
        if (!$activePlan) {
            return 0;
        }
        if ($activePlan->instance_limit === null) {
            return 'Illimité';
        }
        return max(0, $activePlan->instance_limit - $this->instances()->count());
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

}
