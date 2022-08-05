<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCourseUnit extends Model
{
    protected $table = 'user_courses';
    public $timestamps = false;
    // public $incrementing = false;
    protected $fillable = [
    'user_id',
    'allocation_content_id',
    ];
}
