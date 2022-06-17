<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAvatar extends Model
{
    protected $table = 'user_avatars';
    protected $fillable = [
        'user_id',
        'avatar_id',
        'url',
        'is_selected',
    ];
}
