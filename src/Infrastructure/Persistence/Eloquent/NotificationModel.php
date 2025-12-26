<?php

namespace Core\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'channels',
        'recipient',
        'payload',
        'status',
        'retry_count',
        'error_message',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'channels' => 'array',
        'payload' => 'array',
        'retry_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
