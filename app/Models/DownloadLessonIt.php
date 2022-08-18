<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DownloadLessonIt extends BaseModel
{

    protected $table = 'download_lesson_it';
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'device_uid',
        'lesson_ids',
        'download_at',
        'url',
        'is_created',
    ];
}
