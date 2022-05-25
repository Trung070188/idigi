<?php

namespace App\Http\Controllers\Admin;


use App\Models\GroupPermission;
use App\Models\Permission;
use App\Models\RoleHasPermission;
use App\Models\UserRole;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;
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
        $jsonData = [
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
            'message' => 'Đã xóa'
        ];
    }

    /**
     * @uri  /xadmin/roles/save
     * @return  array
     */
    public function save(Request $req)
    {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');

        $rules = [
            'role_name' => 'required|max:45',
//    'role_description' => 'max:255',
        ];

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
                'id' => $entry->id
            ];
        } else {
            $entry = new Role();
            $entry->fill($data);
            $entry->save();

            return [
                'code' => 0,
                'message' => 'Đã thêm',
                'id' => $entry->id
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
            ->orderBy('id', 'ASC')->get();
        $groupPermissions = GroupPermission::with(['permissions'])->orderBy('name', 'ASC')->get();

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
                'description' => $role->description,
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

    public function export()
    {
        $keys = [
            'role_name' => ['A', 'role_name'],
            'role_description' => ['B', 'role_description'],
        ];

        $query = Role::query()->orderBy('id', 'desc');

        $entries = $query->paginate();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($keys as $key => $v) {
            if (is_string($v)) {
                $sheet->setCellValue($v . "1", $key);
            } elseif (is_array($v)) {
                list($c, $n) = $v;
                $sheet->setCellValue($c . "1", $n);
            }
        }


        foreach ($entries as $index => $entry) {
            $idx = $index + 2;
            foreach ($keys as $key => $v) {
                if (is_string($v)) {
                    $sheet->setCellValue("$v$idx", data_get($entry->toArray(), $key));
                } elseif (is_array($v)) {
                    list($c, $n) = $v;
                    $sheet->setCellValue("$c$idx", data_get($entry->toArray(), $key));
                }
            }
        }
        $writer = new Xlsx($spreadsheet);
        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel');
        $filename = uniqid() . '-' . date('Y_m_d H_i') . ".xlsx";

        // It will be called file.xls
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Write file to the browser
        $writer->save('php://output');
        die;
    }

    public function changeRolePermission(Request $req)
    {
        $roleId = $req->role_id;
        $permissionId = $req->permission_id;
        $check = $req->check;


        if ($check == 0) {
            RoleHasPermission::where('role_id', $roleId)
                ->where('permission_id', $permissionId)
                ->delete();
        } else {
            RoleHasPermission::updateOrCreate([
                'role_id' => $roleId,
                'permission_id' => $permissionId
            ],
                ['role_id' => $roleId,
                    'permission_id' => $permissionId
                ]);
        }

        return [
            'code' => 0,
            'message' => 'Đã cập nhật'
        ];
    }
}
