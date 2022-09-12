<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZipPlanLesson extends Model
{
    public $timestamps = true;
    protected $table = 'zip_plan_lessons';
    protected  $fillable = [
        'plan_id',
        'lesson_ids',
        'url',
        'status',
        'type',
        'created_at',
        'updated_at',
    ];
}
