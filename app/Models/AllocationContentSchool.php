<?php
namespace App\Models;


 /**
 * @property int       $id
 * @property int       $allocation_id
 * @property int       $school_id
 */
class AllocationSchool extends BaseModel
{
    protected $table = 'allocation_content_schools';
    protected $fillable = [
    'allocation_id',
    'school_id',
];
}
