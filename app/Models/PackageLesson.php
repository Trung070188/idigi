<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageLesson extends Model
{
    protected $table = 'package_lessons';
    protected $fillable = [
        'name',
        'plan_id',
        'lesson_ids',
        'status',
        'created_at',
        'updated_at',
    ];
}
