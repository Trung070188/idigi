<?php

namespace App\Helpers;



use App\Models\Permission;
use App\Models\RoleHasPermissonDetail;
use Illuminate\Support\Facades\Auth;

class PermissionField
{
    public function permission($user = NULL){
        if(!$user){
            $user = Auth::user();
        }

        $roles = $user->roles;

        $roleIds = [];
        foreach ($roles as $role){
            $roleIds[] = $role->id;
        }

        $permissions = RoleHasPermissonDetail::whereIn('role_id', $roleIds)
            ->with('permissionDetail')
            ->get();

        return $permissions;

    }

    public function havePermission($field, $permissions, $user){
        if(!$user){
            $user = Auth::user();
        }

        $roles = $user->roles;

        foreach ($roles as $role){
            if($role->role_name == "Super Administrator"){
                return true;
            }

        }

        foreach ($permissions as $permission){
            if(@$permission->permissionDetail->code == $field){
                return true;
            }
        }

        return false;
    }
}
