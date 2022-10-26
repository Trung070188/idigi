<?php
namespace App\Models;


 /**
 * @property int       $id
 * @property string    $display_name
 * @property string    $name
 * @property string    $code
 * @property int       $permision_id
 * @property int       $order
 * @property string    $created_at
 * @property string    $updated_at
 * @property string    $deleted_at
 */
class PermissionDetail extends BaseModel
{
    protected $table = 'permission_details';
    protected $fillable = [
    'display_name',
    'name',
    'code',
    'permission_id',
    'order',
];

}
