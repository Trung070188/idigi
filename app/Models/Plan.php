<?php
namespace App\Models;


 /**
 * @property int       $id
 * @property string    $name
 * @property string    $status
 * @property string    $Deployed
 * @property int       $created_by
 * @property \DateTime $created_at
 * @property \DateTime $due_at
 */
class Plan extends BaseModel
{
    protected $table = 'plans';
    protected $fillable = [
    'name',
    'status',
    'deployed',
    'created_by',
    'due_at',
    'plan_description',
    'user_id',
    'secret_key',
     'export_devices'
];
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'plan_lessons');
    }
    public function planLesson()
    {
        return $this->hasMany(PlanLesson::class,'plan_id');
    }
    public function schools()
    {
        return $this->belongsToMany(School::class,'school_plans');
    }
}
