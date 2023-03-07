<?php

namespace App\Models;


/**
 * @property int $id
 * @property string $title
 * @property string $total_school
 * @property string $total_course
 * @property string $total_unit
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class District extends BaseModel
{
    protected $table = 'districts';
    protected $fillable = [
        'province_id',
        'name',
    ];

    public function school(){
        return $this->belongsTo(Province::class);
    }

}
