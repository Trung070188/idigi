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
        'plan_id',
        'type',
        'secret_key',
        'reason',
        'delete_request',
        'key_collection_id',
        'expire_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
