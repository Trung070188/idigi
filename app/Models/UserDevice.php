<?php


namespace App\Models;


class UserDevice extends BaseModel
{
    public $timestamps = true;
    protected $table = 'user_devices';
    protected  $fillable = [
        'device_uid',
        'device_name',
        'user_id',
        'status',
        'secret_key',
        'reason',
        'created_at',
        'updated_at'
    ];
    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
