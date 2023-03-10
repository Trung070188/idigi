<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $created_by
 * @property \DateTime $created_date
 * @property string $enabled
 * @property int $grade
 * @property string $last_modified_by
 * @property \DateTime $last_modified_date
 * @property string $name
 * @property string $rating
 * @property string $shared
 * @property string $structure
 * @property string $subject
 * @property int $unit
 * @property string $number
 * @property string $customized
 */
class Lesson extends BaseModel
{
    use SoftDeletes;

    protected $table = 'lessons';
    protected $fillable = [
        'enabled',
        'grade',
        'name',
        'rating',
        'shared',
        'structure',
        'subject',
        'description',
        'unit',
        'unit_id',
        'course_id',
        'unit_name',
        'number',
        'customized',
        'old_id',
        'level',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    public function inventories(){
        return $this->belongsToMany(Inventory::class, 'lesson_inventory')->orderBy("lesson_inventory.order", "ASC");
    }
    public function user_units()
    {
        return $this->hasMany(UserUnit::class,'unit_id','unit_id');
    }
    public function lessonPlan()
    {
        return $this->hasMany(PlanLesson::class,'lesson_id');

    }
}
