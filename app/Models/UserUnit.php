<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserUnit extends Model
{
    protected $autoSchema = false;
    protected $primaryKey = NULL;
    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'user_units';
    protected $fillable=[
        'user_id',
        'unit_id',
        'couser_id'
    ];
}
