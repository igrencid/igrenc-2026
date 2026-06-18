<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'referer',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];
}
