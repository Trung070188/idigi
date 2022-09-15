<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZipPlanLesson extends Model
{
    public $timestamps = true;
    protected $table = 'zip_plan_lessons';
    protected  $fillable = [
        'user_id',
        'plan_id',
        'package_id',
        'lesson_ids',
        'url',
        'status',
        'type',
        'created_at',
        'updated_at',
    ];

    public function plan()
    {
        return $this->belongsToMany(Plan::class);
    }
    public function package_lessons()
    {
        return $this->belongsTo(PackageLesson::class,'package_id');
    }
}
