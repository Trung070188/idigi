<?php
namespace App\Models;


 use Illuminate\Database\Eloquent\SoftDeletes;

 /**
 * @property int       $id
 * @property string    $created_by
 * @property \DateTime $created_date
 * @property string    $enabled
 * @property int       $grade
 * @property string    $last_modified_by
 * @property \DateTime $last_modified_date
 * @property string    $name
 * @property string    $rating
 * @property string    $shared
 * @property string    $structure
 * @property string    $subject
 * @property int       $unit
 * @property string    $number
 * @property string    $customized
 */
class Lesson extends BaseModel
{
    use SoftDeletes;
    protected $table = 'lessons';
    protected $fillable = [
    'created_by',
    'created_date',
    'enabled',
    'grade',
    'last_modified_by',
    'last_modified_date',
    'name',
    'rating',
    'shared',
    'structure',
    'subject',
    'unit',
    'number',
    'customized',
];
}
