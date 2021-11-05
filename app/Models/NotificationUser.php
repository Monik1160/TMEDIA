<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationUser extends Model
{
    use SoftDeletes;

    protected $table = 'notifications_user';

    protected $fillable = ['notifications_id', 'user_id', 'read_at'];

    public function notification() {
        return $this->belongsTo(Notifications::class);
    }

    public function user() {
        return $this->belongsTo(\App\User::class);
    }
}
