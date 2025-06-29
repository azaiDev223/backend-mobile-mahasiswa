<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = [
        'sender_id',
        'sender_type',
        'receiver_id',
        'receiver_type',
        'message',
        'read_at',
    ];

    // Relasi polimorfik untuk pengirim pesan
    public function sender()
    {
        return $this->morphTo();
    }

    // Relasi polimorfik untuk penerima pesan
    public function receiver()
    {
        return $this->morphTo();
    }
}
