<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrafficBlockLog extends Model
{
    protected $fillable = [
        'ip_address',
        'method',
        'route',
        'url',
        'user_type',
        'user_id',
        'reason',
        'limit',
        'retry_after',
    ];
}