<?php


namespace App\Models;


class UserDevice extends BaseModel
{
    protected $table = 'user_devices';
    protected  $fillable = [
        'device_uid',
        'device_name',
        'user_id',
        'status'
    ];
    public function user()
    {
        return $this->hasOne(User::class,'user_id');
    }
}
