<?php

namespace App\Http\Controllers\Admin;


use App\Helpers\PermissionField;
use App\Models\GroupPermission;
use App\Models\Permission;
use App\Models\RoleHasPermission;
use App\Models\UserRole;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class RolesController extends AdminBaseController
{
//    public static $menus = [
//        [
//            'name' => 'Role',
//            'icon' => 'fa fa-shopping-cart',
//            'url' => '/xadmin/roles/index',
//        ]
//    ];

    /**
     * Index page
     * @uri  /xadmin/roles/index
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function index(Request $req)
    {
        $title = 'Role';
        $component = 'RoleIndex';
        $permissions = Permission::query()->orderBy('name')->get();
        $user = Auth::user();
        $permissionDetail = new PermissionField();
        $permission = $permissionDetail->permission($user);
        $permissionFields = [
            'role_add_new' => $permissionDetail->havePermission('role_add_new',$permission,$user),
            'role_name'=>$permissionDetail->havePermission('role_name',$permission,$user),
            'role_description'=>$permissionDetail->havePermission('role_description',$permission,$user),
            'role_set'=>$permissionDetail->havePermission('role_set',$permission,$user),
        ];
        $jsonData = [
            'permissionFields'=>$permissionFields,
            'permissions' => $permissions,
//            'entry' => $entry,
        ];

        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }

    /**
     * Create new entry
     * @uri  /xadmin/roles/create
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function create()
    {
        $component = 'RoleForm';
        $title = 'Create roles';
        return component($component, compact('title'));
    }

    /**
     * @uri  /xadmin/roles/edit?id=$id
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function edit(Request $req)
    {
        $id = $req->id;
        $entry = Role::find($id);

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        /**
         * @var  Role $entry
         */

        $title = 'Edit';
        $component = 'RoleForm';


        return component($component, compact('title', 'entry'));
    }

    /**
     * @uri  /xadmin/roles/remove
     * @return  array
     */
    public function remove(Request $req)
    {
        $id = $req->id;
        $entry = Role::find($id);

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        RoleHasPermission::where('role_id', $id)->delete();
        UserRole::where('role_id', $id)->delete();
        $entry->delete();
        return [
            'code' => 0,
            'message' => 'Đã xóa',
            'object'=>$entry->role_name,
            'status'=>'deleted role',
            'role'=>$this->roleName()
        ];
    }

    /**
     * @uri  /xadmin/roles/save
     * @return  array
     */
    public function roleName()
    {
        $auth=Auth::user();
        foreach ($auth->roles as $role)
        {
            $roleName=$role->role_name;
        }
        return $roleName;
    }
    public function save(Request $req)
    {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');

        $rules = [
             'role_name' => 'unique:roles,role_name',
//    'role_description' => 'max:255',
        ];
        if (isset($data['id'])) {
            $role = Role::find($data['id']);
            if($data['role_name'])
            {
                $rules['role_name'] = [ Rule::unique('roles')->ignore($role->id),];

            }

        }

        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }

        /**
         * @var  Role $entry
         */
        if (isset($data['id'])) {
            $entry = Role::find($data['id']);
            if (!$entry) {
                return [
                    'code' => 3,
                    'message' => 'Không tìm thấy',
                ];
            }

            $entry->fill($data);
            $entry->save();


            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
                'id' => $entry->id,
                'status'=>'Update role',
                'object'=>$entry->role_name,
                'role'=>$this->roleName(),
            ];
        } else {
            $entry = new Role();
            $entry->fill($data);
            $entry->save();

            return [
                'code' => 0,
                'message' => 'Đã thêm',
                'id' => $entry->id,
                'status'=>'created new role',
                'object'=>$entry->role_name,
                'role'=>$this->roleName(),
            ];
        }
    }

    /**
     * @param Request $req
     */
    public function toggleStatus(Request $req)
    {
        $id = $req->get('id');
        $entry = Role::find($id);

        if (!$id) {
            return [
                'code' => 404,
                'message' => 'Not Found'
            ];
        }

        $entry->status = $req->status ? 1 : 0;
        $entry->save();

        return [
            'code' => 200,
            'message' => 'Đã lưu'
        ];
    }

    /**
     * Ajax data for index page
     * @uri  /xadmin/roles/data
     * @return  array
     */
    public function data(Request $req)
    {
        $roles = Role::query()
            ->with(['permissions'])
            ->where('role_name', '<>','Super Administrator')
            ->orderBy('id', 'ASC')->get();
        $groupPermissions = GroupPermission::with(['permissions'])
            ->whereDoesntHave('childs')
            ->orderBy('order', 'ASC')
            ->where('show_role', 1)
            ->get();

        $data = [];

        foreach ($roles as $role) {
            $rolePermissions = [];
            foreach ($groupPermissions as $groupPermission) {
                foreach ($groupPermission->permissions as $permission) {

                    $item = [
                        'id' => $permission->id,
                        'group_permission' => $groupPermission->id,
                        'value' => 0
                    ];
                    foreach ($role->permissions as $_permission) {
                        if ($_permission->id == $permission->id) {
                            $item['value'] = 1;
                        }
                    }
                    $rolePermissions[] = $item;
                }
            }

            $data[] = [
                'role_name' => $role->role_name,
                'id' => $role->id,
                'role_description' => $role->role_description,
                'allow_deleted' => $role->allow_deleted,
                'permissions' => $rolePermissions

            ];

        }


        return [
            'code' => 0,
            'data' => [
                'roles' => $data,
                'groupPermissions' => $groupPermissions
            ]

        ];
    }


    public function changeRolePermission(Request $req)
    {
        $roleId = $req->role_id;
        $permissionId = $req->permission_id;
        $check = $req->check;

        $permission = Permission::where('id', $permissionId)->first();
        $groupPermission = GroupPermission::where('id', $permission->group_permission_id)->with(['parent'])->first();
        $childCount = Permission::where('group_permission_id', $permission->group_permission_id)
            ->whereHas('roles', function ($q) use ($roleId){
                $q->where('id', $roleId);
            })
            ->where('id', '<>', $permissionId)->count();


        if ($check == false) {
            RoleHasPermission::where('role_id', $roleId)
                ->where('permission_id', $permissionId)
                ->delete();

            if($groupPermission && $childCount == 0){//Xóa role ở group

                $permissionRole = $groupPermission->role_id;
                $newRole = [];
                $roles = explode(';', $permissionRole);
                foreach ($roles as $role){
                    if($role != $roleId){
                        $newRole[] = $role;
                    }
                }
                $groupPermission->role_id = implode(';', $newRole);
                $groupPermission->save();


                if($groupPermission->parent_id){//Xóa role ở parent_group
                    $parentPermission = GroupPermission::where('id', $groupPermission->parent_id)->first();
                    $childIds = GroupPermission::where('parent_id', $groupPermission->parent_id)
                        ->where('id','<>', $groupPermission->id)
                        ->pluck('id')->toArray();

                    $childCount = Permission::whereHas('roles', function ($q) use ($roleId){
                            $q->where('id', $roleId);
                        })
                        ->whereIn('group_permission_id', $childIds)->count();

                    if($childCount == 0){
                        $permissionRole = $parentPermission->role_id;
                        $newRole = [];
                        $roles = explode(';', $permissionRole);
                        foreach ($roles as $role){
                            if($role != $roleId){
                                $newRole[] = $role;
                            }
                        }
                        $parentPermission->role_id = implode(';', $newRole);
                        $parentPermission->save();
                    }
                }

            }

        } else {
            RoleHasPermission::updateOrCreate([
                'role_id' => $roleId,
                'permission_id' => $permissionId
            ],
                ['role_id' => $roleId,
                'permission_id' => $permissionId
                ]);

            if($groupPermission){
                $roles = explode(';', $groupPermission->role_id);
                if(!in_array($roleId, $roles)){
                    $roles[] = $roleId;
                    $groupPermission->role_id = implode(';', $roles);
                    $groupPermission->save();

                    if($groupPermission->parent_id){
                        $parentPermission = GroupPermission::where('id', $groupPermission->parent_id)->first();
                        $roles = explode(';', $parentPermission->role_id);
                        if(!in_array($roleId, $roles)){
                            $roles[] = $roleId;
                            $parentPermission->role_id = implode(';', $roles);
                            $parentPermission->save();
                        }

                    }
                }
            }
        }

        return [
            'code' => 0,
            'message' => 'Đã cập nhật'
        ];
    }
}
