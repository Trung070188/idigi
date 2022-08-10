<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolCourse extends Model
{
    protected $autoSchema = false;
    protected $primaryKey = NULL;
    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'school_courses';
    protected $fillable=[
        'school_id',
        'course_id',
        'unit_id',
    ];
}
