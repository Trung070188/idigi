<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DownloadLessonLog extends BaseModel
{
    use SoftDeletes;

    protected $table = 'download_lesson_log';
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'device_uid',
        'app_id',
        'download_at',
        'lesson_ids',
    ];
}
