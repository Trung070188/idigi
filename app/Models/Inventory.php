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
        'type',
        'subject',
        'grade',
        'name',
    ];
}
