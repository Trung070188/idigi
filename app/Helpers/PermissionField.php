<?php

namespace App\Helpers;



use App\Models\Permission;
use App\Models\RoleHasPermissonDetail;
use Illuminate\Support\Facades\Auth;

class PermissionField
{
    public function havePermission($field, $user = NULL){

        if(!$user){
            $user = Auth::user();
        }

        $roles = $user->roles;

        $isAdmin = 0;
        $roleIds = [];
        foreach ($roles as $role){
            $roleIds[] = $role->id;
            if($role->role_name == "Super Administrator"){
                $isAdmin = 1;
            }

        }

        if($isAdmin == 1){
            return true;
        }else{


            $rolePermissionCount = RoleHasPermissonDetail::whereIn('role_id', $roleIds)
                ->whereHas('permissionDetail',function($q) use ($field){
                    $q->where('code',$field);
                })
                ->count();


            if($rolePermissionCount > 0){
                return true;
            }
        }

        return false;

    }
}
