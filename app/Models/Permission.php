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
        'group_permission_id'
    ];


    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions');

    }

    public function groupPermission(){
        return $this->belongsTo(GroupPermission::class, 'group_permission_id');
    }
}
