<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $last_login
 * @property string $avatar
 * @property \DateTime $birthday
 * @property string $phone
 * @property string $deleted_at
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'full_name',
        'phone',
        'school_id',
        'class',
        'image',
        'file_image_id',
        'email',
        'password',
        'description',
        'last_login',
        'state',
        'active_allocation',
        'full_name_active_content',
        'sso_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    /**
     * Scope a query to only include popular users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCreatedIn($query, $dateRange)
    {

        $createdRange = date_parse_range($dateRange);

        if ($createdRange) {
            $query->where('created_at', '>=', $createdRange['start'] . ' 00:00:00')
                ->where('created_at', '<=', $createdRange['end'] . ' 23:59:59');

        }

        return $query;
    }

    public function getAvatar()
    {
        if ($this->avatar) {
            return $this->avatar;
        }

        return asset('/assets/avatar/?name=' . urlencode($this->name));
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id');
    }
    public function user_roles()
    {
        return $this->hasMany(UserRole::class);
    }

    public function request_roles()
    {
        return $this->hasMany(RequestRole::class);
    }

    public function user_devices()
    {
        return $this->hasMany(UserDevice::class);
    }

    public function fileImage()
    {
        return $this->belongsTo(File::class, 'file_image_id');
    }

    public function notification()
    {
        return $this->hasMany(Notification::class);
    }
    public function schools()
    {
        return $this->belongsTo(School::class,'school_id');
    }
    public function user_cousers()
    {
        return $this->hasMany(UserCourseUnit::class,'user_id');
    }
    public function user_units()
    {
        return $this->hasMany(UserUnit::class,'user_id');
    }
    public function units()
    {
        return $this->belongsToMany(Unit::class,'user_units');
    }
    public function cousers()
    {
        return $this->belongsToMany(Course::class,'user_courses');
    }

}
