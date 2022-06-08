<?php
namespace App\Models;


 /**
 * @property int       $id
 * @property string    $school_name
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
    'school_name',
    'school_address',
    'school_email',
    'school_phone',
    'license_info',
    'license_to',
    'license_state',
    'number_of_users',
    'devices_per_user',
];
    public function users(){
        return $this->hasMany(User::class);
    }
}
