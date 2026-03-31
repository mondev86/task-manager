<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'completed',
        'status',
        'priority',
        'start_time',
        'end_time',
        'whatsapp_number',
        'whatsapp_url',
        'notification_sent',
        'email'
    ];

    protected $casts = [
        'completed' => 'boolean',
        'notification_sent' => 'boolean',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
}
