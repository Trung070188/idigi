<?php
namespace App\Models;


 use Illuminate\Database\Eloquent\SoftDeletes;

 /**
 * @property int       $id
 * @property string    $label
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class Unit extends BaseModel
{
    use SoftDeletes;
    protected $table = 'units';
    protected $fillable=[
        'unit_name',
        'subject',
        'description',
        'course_id',
        'active',
        'position'
    ];

    public function lessons(){
        return $this->hasMany(Lesson::class, 'unit_id')->orderBy('lessons.position', 'ASC')->orderBy('lessons.id', 'ASC');
    }
    public function user_units()
    {
        return $this->hasMany(UserUnit::class, 'unit_id');
    }
    public function school_units()
    {
        return $this->hasMany(SchoolCourseUnit::class, 'unit_id');
    }

    public function course(){
        return $this->belongsTo(Course::class, 'course_id');
    }

}
