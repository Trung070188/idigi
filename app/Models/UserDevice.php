<?php


namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

class UserDevice extends BaseModel
{
    public $timestamps = true;
    use SoftDeletes;
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
