<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DownloadInventoryLog extends BaseModel
{
    use SoftDeletes;

    protected $table = 'download_inventory_log';
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'device_uid',
        'lesson_id',
        'inventory',
        'download_at'
    ];
}
