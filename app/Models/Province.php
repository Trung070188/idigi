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
class Province extends BaseModel
{
    protected $table = 'provinces';
    protected $fillable = [
        'country_id',
        'name',
        'region_id'
    ];

    public function districts(){
        return $this->hasMany(District::class);
    }

}
