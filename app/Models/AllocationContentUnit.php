<?php
namespace App\Models;


 /**
 * @property int       $id
 * @property int       $allocation_content_id
 * @property int       $course_id
 * @property int       $course_component_id
 */
class AllocationContentUnit extends BaseModel
{
    protected $table = 'allocation_content_units';
    public $timestamps = false;
    protected $fillable = [
    'allocation_content_id',
    'course_id',
    'unit_id',
];
}
