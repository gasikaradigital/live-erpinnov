<?php

namespace App\Models;

use Illuminate\Support\Str;
use Laravel\Paddle\Subscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'paddle_plan_id',
        'uuid',
        'name',
        'description',
        'price_monthly',
        'price_yearly',
        'instance_limit',
        'duration_days',
        'is_free',
        'is_default',
        'features',
    ];

    protected $casts = [
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
        'instance_limit' => 'integer',
        'duration_days' => 'integer',
        'is_free' => 'boolean',
        'is_default' => 'boolean',
        'features' => 'array',
    ];

    public function getDiscountedYearlyPriceAttribute()
    {
        // Si le prix annuel est déjà réduit, averina tsotra'izao
        if ($this->price_yearly <= $this->price_monthly * 12 * 0.9) {
            return $this->price_yearly;
        }
        // Sinon, applique la réduction de 10%
        return $this->price_monthly * 12 * 0.9;
    }

    public static function boot()
    {
        parent::boot();
        static::saving(function ($plan) {
            if ($plan->is_default) {
                self::where('is_default', true)->where('id', '!=', $plan->id)->update(['is_default' => false]);
            }
        });

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'paddle_id', 'paddle_plan_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
