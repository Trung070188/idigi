<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends BaseModel
{
    protected $table = 'roles';
    protected $fillable = [
        'role_name',
        'role_description',
        'allow_deleted',
        'check_delete_device_request'

    ];

public function permissions(){
    return $this->belongsToMany(Permission::class,'role_has_permissions','role_id','permission_id');
}
public function permissionDetails(){
    return $this->belongsToMany(PermissionDetail::class,'role_has_permission_details','role_id','permission_detail_id');
}
    public function users()
    {
        return $this->belongsToMany(User::class,'user_role');
    }
}
