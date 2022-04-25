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
class Permission extends BaseModel
{
    protected $autoSchema = false;
    protected $table = 'permissions';
    protected $fillable = [
        'name'
    ];

    public static function getTree()
    {
        $all = static::all();
        $parentMap = [];
        foreach ($all as $e) {
            $parentMap[$e->parent_id] [] = $e;
        }

        $tree = [];
        foreach ($all as $e) {
            $e->data_children = $parentMap[$e->id] ?? [];
        }

        foreach ($all as $e) {
            if ($e->parent_id == 0) {
                $tree[] = $e;
            }
        }

        return $tree;
    }
}
