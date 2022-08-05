<?php
namespace App\Models;


 /**
 * @property int       $id
 * @property string    $title
 * @property string    $total_school
 * @property string    $total_course
 * @property string    $total_unit
 * @property int       $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class AllocationContent extends BaseModel
{
    protected $table = 'allocation_contents';
    protected $fillable = [
    'title',
    'total_school',
    'total_course',
    'total_unit',
    'status',
];
public function schools()
{
    return $this->belongsToMany(School::class, 'allocation_content_schools');
}
public function courses()
{
    return $this->belongsToMany(Course::class,'allocation_content_courses');
}
public function units()
{
    return $this->belongsToMany(Unit::class,'allocation_content_units');
}
public function coursess()
{
    return $this->belongsToMany(Course::class,'allocation_content_units');
}
public function course_unit()
{
    return $this->hasMany(AllocationContentUnit::class,'allocation_content_id');
}
}
