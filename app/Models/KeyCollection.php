<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeyCollection extends Model
{
    use HasFactory;
    protected $table = 'key_collections';
    protected $fillable=[
        'title',
        'description',
        'number_key',
        'status',
        'creation_date'
    ];
}
