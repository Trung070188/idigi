<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
/**
 * @property int       $id
 * @property string    $name
 * @property string    $email
 * @property string    $password
 * @property string    $remember_token
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $last_login
 * @property string    $avatar
 * @property \DateTime $birthday
 * @property string    $phone
 * @property string    $deleted_at
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
        'email',
        'description',
        'state',
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
     * @param $id
     * @return bool
     */
    public function hasGroup($id): bool
    {
        static $map = [];

        if (empty($map)) {
            $userGroups = DB::select('SELECT * FROM user_groups p
INNER JOIN groups g ON g.`id`=p.`group_id`
WHERE p.`user_id`=?', [$this->id]);
            foreach ($userGroups as $g) {
                $map[$g->group_id] = true;
            }
            //dd($userGroups);
        }


        if (isset($map[Role::SUPER_USER])) {
            return true;
        }

        return isset($map[$id]);
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCreatedIn($query, $dateRange) {

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

    public function roles(){
        return $this->belongsToMany(Role::class,'user_role');
    }
}
