<?php
namespace App\Models;


 use Illuminate\Database\Eloquent\SoftDeletes;

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
    use SoftDeletes;
    protected $table = 'courses';
    protected $fillable = [
    'course_name',
    'subject',
    'grade',
    'description',
    'public_from',
    'public_to',
    'active',
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
        return $this->hasMany(Unit::class,'course_id')->orderBy('units.position', 'ASC');
    }
}
