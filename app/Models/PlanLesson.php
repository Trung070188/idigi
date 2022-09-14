<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanLesson extends Model
{
    protected $autoSchema = false;
    protected $primaryKey = NULL;
    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'plan_lessons';
    protected $fillable=[
        'plan_id',
        'lesson_id',
        'package_id',
    ];
}
