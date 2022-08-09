<?php

namespace App\Models;


/**
 * @property int $id
 * @property int $allocation_content_id
 * @property int $school_id
 */
class AllocationContentSchool extends BaseModel
{
    protected $table = 'allocation_content_schools';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
        'allocation_content_id',
        'school_id',
    ];

    public function allocation_content(){
        return $this->belongsTo(AllocationContent::class, 'allocation_content_id');
    }
}
