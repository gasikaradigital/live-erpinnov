<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    use HasFactory;

    protected $fillable = [
        '',
        'description',
        'price_monthly',
        'price_yearly',
        'price_local',
        'instance_limit',
        'duration_days',
        'is_free',
        'is_default',
        'features',
    ];
}
