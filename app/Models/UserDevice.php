<?php


namespace App\Models;


class UserDevice extends BaseModel
{
    protected $table = 'user_devices';
    protected  $fillable = [
        'device_uid',
        'device_name',
        'user_id'
    ];
}
