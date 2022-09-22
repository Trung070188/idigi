<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolPlan extends Model
{
    protected $autoSchema = false;
    protected $primaryKey = NULL;
    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'school_plans';
    protected $fillable=[
        'school_id',
        'plan_id',
    ];
}

