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
    'public_from',
    'public_to',
    'status',
];
 public function units()
 {
     return $this->belongsToMany(Unit::class,'allocation_content_units');
 }
}
