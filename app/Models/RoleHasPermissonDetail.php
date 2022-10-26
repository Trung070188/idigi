<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleHasPermissonDetail extends Model
{
    protected $autoSchema = false;
    protected $primaryKey = NULL;
    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'role_has_permission_details';
    protected $fillable=[
        'permission_detail_id',
        'role_id',
    ];

    public function permissionDetail(){
        return $this->belongsTo(PermissionDetail::class);
    }

}
