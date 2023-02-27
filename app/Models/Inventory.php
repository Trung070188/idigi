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
        'file_image_id',
        'file_asset_id',
        'old_id',
        'level',
        'location',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    public function fileImage(){
        return $this->belongsTo(File::class, 'file_image_id');
    }
    public function fileAsset(){
        return $this->belongsTo(File::class, 'file_asset_id');
    }
    public function lessons(){
        return $this->belongsToMany(Lesson::class, 'lesson_inventory');
    }

}
