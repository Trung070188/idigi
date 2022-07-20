<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthenticationLog extends Model
{
    use HasFactory;
    protected $table = 'authentication_log';
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'device_uid',
        'login_at',
        'logout_at',
    ];
}
