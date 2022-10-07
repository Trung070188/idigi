<?php


namespace App\Models;


class LessonInventory extends BaseModel
{
    protected $table = 'lesson_inventory';
    protected  $fillable = [
        'lesson_id',
        'inventory_id',
        'level',
    ];
}
