<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestRole extends BaseModel
{
    protected $table = 'request_roles';

    protected $fillable = [
        'user_id',
        'status',
        'reason',
        'content'
    ];
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
