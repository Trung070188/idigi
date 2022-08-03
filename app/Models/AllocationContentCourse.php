<?php
namespace App\Models;


 /**
 * @property int       $id
 * @property int       $allocation_content_id
 * @property int       $course_id
 */
class AllocationContentCourse extends BaseModel
{
    protected $table = 'allocation_content_courses';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
    'allocation_content_id',
    'course_id',
];
}
