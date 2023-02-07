<?php
namespace App\Models;


 /**
 * @property int       $id
 * @property string    $label
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class Unit extends BaseModel
{
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
