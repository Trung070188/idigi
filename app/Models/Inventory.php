<?php


namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends BaseModel
{
    use SoftDeletes;
    protected $table = 'inventories';
    protected  $fillable = [
        'description',
        'enabled',
        'grade',
        'image',
        'physical_path',
        'virtual_path',
        'type',
        'subject',
        'grade',
        'name',
        'rating',
        'duration',
        'link_webview',
        'slideshows',
        'tags',
        'old_id',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];
}
