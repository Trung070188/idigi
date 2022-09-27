<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCourseUnit extends Model
{
    protected $autoSchema = false;
    protected $primaryKey = NULL;
    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'user_courses';

    protected $fillable = [
    'user_id',
    'course_id',
    'school_id',
    'allocation_content_id'
    ];
}
