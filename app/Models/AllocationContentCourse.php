<?php
namespace App\Models;


 /**
 * @property int       $id
 * @property int       $allocation_id
 * @property int       $course_id
 */
class AllocationCourse extends BaseModel
{
    protected $table = 'allocation_content_courses';
    protected $fillable = [
    'allocation_id',
    'course_id',
];
}
