<?php
namespace App\Models;


 use Illuminate\Database\Eloquent\SoftDeletes;

 /**
 * @property int       $id
 * @property string    $label
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class Unit extends BaseModel
{
    use SoftDeletes;
    protected $table = 'units';
    protected $fillable=[
        'unit_name',
        'subject',
        'description',
        'course_id',
        'active',
        'position'
    ];
}
