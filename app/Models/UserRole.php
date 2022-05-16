<?php


namespace App\Models;


class UserRole extends BaseModel
{
    protected $autoSchema = false;
    protected $primaryKey = NULL;
    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'user_role';
    protected $fillable=[
      'user_id',
      'role_id',
    ];

}
