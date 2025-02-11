<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    const STATUS_TRIAL = 'trial';
    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'plan_id',
        'start_date',
        'sub_plan_id',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function subPlan()
    {
        return $this->belongsTo(SubPlan::class, 'sub_plan_id');
    }


    public function instance()
    {
        return $this->hasOne(Instance::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function isTrialPeriod()
    {
        return $this->status === self::STATUS_TRIAL;
    }
}
