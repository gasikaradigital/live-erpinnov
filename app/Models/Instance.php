<?php

namespace App\Models;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Instance extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'user_id',
        'reference',
        'name',
        'entreprise_id',
        'url',
        'status',
        'subscription_id',
        'expiration_date',
        'token_expires_at',
        'dolibarr_username',
        'dolibarr_password',
        'dolibarr_api_key',
        'auth_token',
        'pays',
    ];


    protected $hidden = ['auth_token', 'dolibarr_password'];

    protected $casts = [
        'expiration_date' => 'datetime',
        'token_expires_at' => 'datetime',
        'pays' => 'integer',
    ];

    public function getUrlAttribute($value)
    {
        return 'http://' . $this->name . '.erpinnov.com';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function isFree()
    {
        return $this->subscription->plan->is_free;
    }

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public static function generateNextReference()
    {
        $latestInstance = self::withTrashed()->latest('id')->first();
        $number = $latestInstance ? ((int) substr($latestInstance->reference, 4)) + 1 : 1;
        return 'REF-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($instance) {
            $instance->auth_token = self::generateUniqueAuthToken();
            $instance->token_expires_at = Carbon::now()->addDays(30);
            $instance->reference = self::generateNextReference();
        });
    }

    public static function generateUniqueAuthToken()
    {
        do {
            $token = Str::random(64);
        } while (static::where('auth_token', $token)->exists());
        return $token;
    }

    // public function regenerateAuthToken()
    // {
    //     $this->update([
    //         'auth_token' => self::generateUniqueAuthToken(),
    //         'token_expires_at' => Carbon::now()->addDays(30),
    //     ]);
    // }

    public function isTokenExpired()
    {
        return $this->token_expires_at->isPast();
    }

    public function setAuthTokenAttribute($value)
    {
        $this->attributes['auth_token'] = $value;
        $this->attributes['token_expires_at'] = Carbon::now()->addDays(30);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }


    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRED);
    }

    public function scopePaid($query)
    {
        return $query->whereHas('subscription.plan', function ($q) {
            $q->where('is_free', false);
        });
    }

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }


    public function getPaysNomAttribute()
    {
        // Si vous utilisez des valeurs numériques
        return $this->pays === 0 ? 'Madagascar' : 'France';
    }
    
    public function getFormattedDate()
    {
        return Carbon::parse($this->created_at)->format('d/m/Y');
    }
}
