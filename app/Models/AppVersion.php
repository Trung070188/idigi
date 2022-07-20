<?php
namespace App\Models;


 /**
 * @property int       $id
 * @property string    $name
 * @property string    $type
 * @property string    $url
 * @property string    $path
 * @property int       $is_default
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class AppVersion extends BaseModel
{
    protected $table = 'app_versions';
    protected $fillable = [
    'name',
    'type',
    'release_note',
    'version',
    'url',
    'path',
    'is_default',
    'release_date',
];
}
