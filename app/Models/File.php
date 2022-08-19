<?php

namespace App\Models;

/**
 * @property int       $id
 * @property string    $name
 * @property string    $path
 * @property string    $url
 * @property int       $size
 * @property int       $is_image
 * @property int       $width
 * @property int       $height
 * @property string    $type
 * @property string    $hash
 * @property string    $extension
 * @property string    $uploaded_by
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class File extends BaseModel
{
    protected $table = 'files';
    protected $casts = [
        'is_image' => 'bool'
    ];
}
