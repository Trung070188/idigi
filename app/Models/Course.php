<?php
namespace App\Models;


 /**
 * @property int       $id
 * @property int       $name
 * @property \DateTime $public_from
 * @property \DateTime $public_to
 * @property int       $status
 * @property \DateTime $created_at
 */
class Course extends BaseModel
{
    protected $table = 'courses';
    protected $fillable = [
    'course_name',
    'subject',
    'grade',
    'description',
    'public_from',
    'public_to',
    'status',
];
    public function units()
    {
        return $this->belongsToMany(Unit::class,'allocation_content_units');
    }
    public function unitSchool()
    {
        return $this->hasMany(SchoolCourseUnit::class,'course_id');
    }
    public function unit()
    {
        return $this->hasMany(Unit::class,'course_id');
    }
}
