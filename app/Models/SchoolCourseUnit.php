<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolCourseUnit extends Model
{
    protected $autoSchema = false;
    protected $primaryKey = NULL;
    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'school_course_units';
    protected $fillable=[
        'school_id',
        'course_id',
        'unit_id',
        'allocation_content_id'
    ];
}
