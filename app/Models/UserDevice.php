<?php


namespace App\Models;


class UserDevice extends BaseModel
{
    public $timestamps = false;
    protected $table = 'user_devices';
    protected  $fillable = [
        'device_uid',
        'device_name',
        'user_id',
        'status'
    ];
    public function user()
    {
        return $this->hasMany(User::class,'user_id');
    }
}
