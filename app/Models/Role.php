<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends BaseModel
{
    protected $table = 'roles';
    protected $fillable = [
        'role_name',
        'role_description'
    ];

public function permissions(){
    return $this->belongsToMany(Permission::class,'role_has_permissions','role_id','permission_id');

}
}
