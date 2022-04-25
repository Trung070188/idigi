<?php

namespace App\Models;


/**
 * @property int $id
 * @property string $name
 * @property string $value
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property string $title
 * @property string $description
 */
class Option extends BaseModel
{
    protected $table = 'options';
    protected $fillable = [
        'name',
        'value',
        'title',
        'description',
    ];
}
