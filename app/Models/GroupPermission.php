<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @property int       $id
 * @property string    $name
 * @property string    $class
 * @property string    $action
 * @property int      $parent_id
 * @property string    $display_name
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class GroupPermission extends BaseModel
{
    protected $autoSchema = false;
    protected $table = 'group_permissions';
    protected $fillable = [
        'name',
        'description',
    ];

    public function permissions(){
        return $this->hasMany(Permission::class);
    }
}
