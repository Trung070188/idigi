<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $class
 * @property string $action
 * @property int $parent_id
 * @property string $display_name
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class Permission extends BaseModel
{
    protected $autoSchema = false;
    protected $table = 'permissions';
    protected $fillable = [
        'name',
        'description',
        'path',
        'code',
        'group_permission_id',
        'display_permission_detail'
    ];


    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions');

    }
    public function permissionDetails()
    {
        return $this->hasMany(PermissionDetail::class,'permission_id')->orderBy('order','desc');
    }
    public function groupPermission(){
        return $this->belongsTo(GroupPermission::class, 'group_permission_id');
    }
}
