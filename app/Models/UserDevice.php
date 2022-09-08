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
        'type',
        'secret_key',
        'reason',
        'key_collection_id',
        'created_at',
        'updated_at'
    ];
    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
