<?php

namespace App\Models\Notification;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table      = 'notifications';
    protected $fillable   = [
        'user_id', 'title', 'message', 'link',
        'is_read'
    ];

    public function user(){return $this->belongsTo(User::class, 'user_id', 'id');}
}
