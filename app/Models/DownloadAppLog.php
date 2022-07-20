<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadAppLog extends Model
{
    use HasFactory;
    protected $table='download_app_log';
    protected $fillable=[
        'user_id',
        'ip_address',
        'user_agent',
        'device_uid',
        'lesson_id',
        'download_at',
    ];
}
