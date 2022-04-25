<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Permission;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class PermissionsController extends AdminBaseController
{
    /**
    * Index page
    * @uri  /xadmin/permissions/index
    * @throw  NotFoundHttpException
    * @return  View
    */
    public function index() {
        $title = 'Permission';
        $component = 'PermissionIndex';
        return vue(compact('title', 'component'));
    }

    public function assigns(Request $req)
    {
        if ($req->isMethod('POST')) {
            $table  = $req->get('table');
            $values  = $req->get('values');
            $entryId = $req->get('entry_id');
            if ($table === 'user_permissions') {
                $field = 'user_id';
            } else if ($table === 'role_permissions') {
                $field = 'role_id';
            } else {
                return [
                    'code' => 404,
                    'message' => 'Invalid table'
                ];
            }

            DB::beginTransaction();
            DB::table($table)->where($field, $entryId)->delete();
            $bulkData = array_map(function($v) use($field, $entryId) {
                return [
                    $field => $entryId,
                    'permission_id' => $v
                ];
            }, $values);
            DB::table($table)->insert($bulkData);
            DB::commit();

            return [
                'code' => 200,
                'message' => 'Đã lưu'
            ];
        }

        $entryId = 0;
        $table = '';
        $field = '';

        if (isset($req->user_id)) {
            $table = 'user_permissions';
            $field = 'user_id';
            $entryId = $req->user_id;
        } elseif (isset($req->role_id)) {
            $table = 'role_permissions';
            $field = 'role_id';
            $entryId = $req->role_id;
        }

        if (!$table) {
            abort(404);
        }

        $permissionValues = DB::table($table)->where($field, $entryId)->get();
        $values = [];
        foreach ($permissionValues as $v) {
            $values[] = $v->permission_id;
        }

        $model = [
            'table' => $table,
            'entry_id' => $entryId,
            'values' => $values
        ];

        $title = 'Phân quyền';
        $component = 'PermissionAssign';
        $tree = Permission::getTree();

        return vue(compact('title', 'component'), compact('tree', 'model'));
    }


    /**
    * Create new entry
    * @uri  /xadmin/permissions/create
    * @throw  NotFoundHttpException
    * @return  View
    */
    public function create (Request $req) {
        $component = 'PermissionForm';
        $title = 'Create permissions';
        return vue(compact('title', 'component'));
    }

    /**
    * @uri  /xadmin/permissions/edit?id=$id
    * @throw  NotFoundHttpException
    * @return  View
    */
    public function edit (Request $req) {
        $id = $req->id;
        $entry = Permission::find($id);

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        /**
        * @var  Permission $entry
        */
        $jsonData = compact('entry');
        $title = 'Edit';
        $component = 'PermissionForm';


        return vue(compact('title', 'jsonData', 'component'));
    }

    /**
    * @uri  /xadmin/permissions/remove
    * @return  array
    */
    public function remove(Request $req) {
        $id = $req->id;
        $entry = Permission::find($id);

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
    * @uri  /xadmin/permissions/save
    * @return  array
    */
    public function save(Request $req) {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');

        $rules = [
    'name' => 'max:200',
    'class' => 'max:200',
    'action' => 'max:100',
];

        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }

        /**
        * @var  Permission $entry
        */
        if (isset($data['id'])) {
            $entry = Permission::find($data['id']);
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
            $entry = new Permission();
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
        $entry = Permission::find($id);

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
    * @uri  /xadmin/permissions/data
    * @return  array
    */
    public function data(Request $req) {
        $query = Permission::query()->orderBy('id', 'desc');

        if ($req->keyword) {
            //$query->where('title', 'LIKE', '%' . $req->keyword. '%');
        }

        $query->createdIn($req->created);


        $entries = $query->paginate();

        return [
            'code' => 0,
            'data' => $entries->items(),
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
            ]
        ];
    }

    public function export() {
                $keys = [
                            'name' => ['A', 'name'],
                            'class' => ['B', 'class'],
                            'action' => ['C', 'action'],
                            ];

        $query = Permission::query()->orderBy('id', 'desc');

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
