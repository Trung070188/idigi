<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DownloadLessonFile extends BaseModel
{
    use SoftDeletes;

    protected $table = 'download_lesson_files';
    protected $fillable = [
        'download_lesson_log_id',
        'source',
        'path',
        'is_main',
        'is_deleted_file'
    ];
}
