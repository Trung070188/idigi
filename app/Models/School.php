<?php
namespace App\Models;


 /**
 * @property int       $id
 * @property string    $label
 * @property string    $school_address
 * @property string    $school_email
 * @property string    $school_phone
 * @property string    $license_info
 * @property \DateTime $license_to
 * @property string    $license_state
 * @property int       $number_of_users
 * @property int       $devices_per_user
 */
class School extends BaseModel
{
    protected $table = 'schools';
    protected $fillable = [
    'label',
    'school_address',
    'school_email',
    'school_phone',
    'license_info',
    'license_to',
    'license_state',
    'number_of_users',
    'devices_per_user',
    'school_description'
];
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function allocation_contents()
    {
        return $this->belongsToMany(AllocationContent::class,'allocation_content_schools');
    }
    public function allocation_school()
    {
        return $this->hasOne(AllocationContentSchool::class);
    }

    public function school_course_units()
    {
        return $this->hasMany(SchoolCourseUnit::class,'school_id');
    }
    public function units()
    {
        return $this->belongsToMany(Unit::class,'school_course_units');
    }
    public function school_courses()
    {
        return $this->belongsToMany(Course::class,'school_courses');
    }
    public function user_devices()
    {
        return $this->hasMany(UserDevice::class,'school_id');
    }


}
