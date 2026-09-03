<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrafficStatistic extends Model
{
    protected $fillable = [
        'stat_date',
        'stat_minute',
        'total_requests',
        'blocked_requests',
        'public_requests',
        'customer_requests',
        'admin_requests',
        'total_response_time',
        'max_response_time',
    ];

    protected $casts = [
        'stat_date' => 'date',
    ];
}