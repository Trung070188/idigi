<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageLesson extends Model
{
    protected $table = 'package_lessons';
    protected $fillable = [
        'total_lesson',
        'plan_id',
        'package_plan_id',
        'created_at',
        'updated_at',
    ];
}
