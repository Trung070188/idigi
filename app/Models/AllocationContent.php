<?php
namespace App\Models;


 /**
 * @property int       $id
 * @property string    $title
 * @property int       $total_school
 * @property int       $total_course
 * @property int       $total_unit
 * @property int       $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class Allocation extends BaseModel
{
    protected $table = 'allocation_contents';
    protected $fillable = [
    'title',
    'total_school',
    'total_course',
    'total_unit',
    'status',
];
}
