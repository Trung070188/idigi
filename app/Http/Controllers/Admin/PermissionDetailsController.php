<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\RoleHasPermissonDetail;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\PermissionDetail;
use App\Models\Role;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class PermissionDetailsController extends AdminBaseController
{
    public static $menus = [
        [
            'name' => 'PermissionDetail',
            'icon' => 'fa fa-shopping-cart',
            'url' => '/xadmin/permission_details/index',
        ]
    ];

    /**
    * Index page
    * @uri  /xadmin/permission_details/index
    * @throw  NotFoundHttpException
    * @return  View
    */
    public function index() {
        $title = 'PermissionDetail';
        $component = 'PermissionDetailIndex';
        return component($component, compact('title'));
    }

    /**
    * Create new entry
    * @uri  /xadmin/permission_details/create
    * @throw  NotFoundHttpException
    * @return  View
    */
    public function create (Request $req) {
        $component = 'Permission_detailForm';
        $title = 'Create permission_details';
        return component($component, compact('title'));
    }

    /**
    * @uri  /xadmin/permission_details/edit?id=$id
    * @throw  NotFoundHttpException
    * @return  View
    */
    public function edit (Request $req) {
        $id = $req->id;
        $entry = PermissionDetail::find($id);

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        /**
        * @var  PermissionDetail $entry
        */

        $title = 'Edit';
        $component = 'Permission_detailForm';


        return component($component, compact('title', 'entry'));
    }

    /**
    * @uri  /xadmin/permission_details/remove
    * @return  array
    */
    public function remove(Request $req) {
        $id = $req->id;
        $entry = PermissionDetail::find($id);

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        $entry->delete();

        return [
            'code' => 0,
            'message' => 'Đã xóa'
        ];
    }

    /**
    * @uri  /xadmin/permission_details/save
    * @return  array
    */
    public function save(Request $req) {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');

        $rules = [
    'display_name' => 'max:191',
    'name' => 'max:191',
    'code' => 'max:191',
    'permision_id' => 'numeric',
    'order' => 'numeric',
];

        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }

        /**
        * @var  PermissionDetail $entry
        */
        if (isset($data['id'])) {
            $entry = PermissionDetail::find($data['id']);
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
            $entry = new PermissionDetail();
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
    * @param  Request $req
    */
    public function toggleStatus(Request $req)
    {
        $id = $req->get('id');
        $entry = PermissionDetail::find($id);

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
    * @uri  /xadmin/permission_details/data
    * @return  array
    */
    public function data(Request $req) {

        $roles = Role::query()
        ->with(['permissionDetails'])
        ->where('role_name', '<>','Super Administrator')
        ->orderBy('order', 'ASC')->get();
        $permissions=Permission::with(['permissionDetails'])->whereNotNull('display_permission_detail')->get();
       $data=[];
       foreach($roles as $role)
       {
           $rolePermissionsDetail=[];
           foreach ($permissions as $permission)
           {
              foreach ($permission->permissionDetails as $permissionDetail)
              {
                  $item =[
                      'id'=>$permissionDetail->id,
                      'permission'=>$permission->id,
                      'value'=>0,
                      'is_admin'=>$permissionDetail->is_admin
                  ];
                  foreach ($role->permissionDetails as $_permissionDetail)
                  {
                      if($_permissionDetail->id==$permissionDetail->id)
                      {
                          $item['value']=1;
                      }
                  }
                  $rolePermissionsDetail[]= $item;
              }


           }
           $data[]=[
                'role_name' => $role->role_name,
                'id' => $role->id,
                'role_description' => $role->role_description,
                'allow_deleted' => $role->allow_deleted,
                'permission'=>$rolePermissionsDetail,
           ];
       }
        return [
           'code'=> 0,
           'data'=>[
               'roles'=>$data,
               'permissions'=>$permissions,
           ]


        ];
    }
    public function changeDetailPermission(Request $req)
    {
        $roleId = $req->role_id;
        $permissionId = $req->permission_detail_id;
        $check = $req->check;
       if($check==false)
       {
           RoleHasPermissonDetail::where('role_id',$roleId)->where('permission_detail_id',$permissionId)->delete();
       }
       else{
           RoleHasPermissonDetail::updateOrCreate([
               'role_id' => $roleId,
               'permission_detail_id' => $permissionId
           ],
               ['role_id' => $roleId,
                   'permission_detail_id' => $permissionId

               ]);
       }

        return [
            'code' => 0,
            'message' => 'Đã cập nhật'
        ];
    }

    public function export() {
                $keys = [
                            'display_name' => ['A', 'display_name'],
                            'name' => ['B', 'name'],
                            'code' => ['C', 'code'],
                            'permision_id' => ['D', 'permision_id'],
                            'order' => ['E', 'order'],
                            ];

        $query = PermissionDetail::query()->orderBy('id', 'desc');

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
}
