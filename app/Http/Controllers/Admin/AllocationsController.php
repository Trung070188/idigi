<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Allocation;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class AllocationsController extends AdminBaseController
{
    public static $menus = [
        [
            'name' => 'Allocation',
            'icon' => 'fa fa-shopping-cart',
            'url' => '/xadmin/allocations/index',
        ]
    ];

    /**
    * Index page
    * @uri  /xadmin/allocations/index
    * @throw  NotFoundHttpException
    * @return  View
    */
    public function index() {
        $title = 'Allocation';
        $component = 'AllocationIndex';
        return component($component, compact('title'));
    }

    /**
    * Create new entry
    * @uri  /xadmin/allocations/create
    * @throw  NotFoundHttpException
    * @return  View
    */
    public function create (Request $req) {
        $component = 'AllocationForm';
        $title = 'Create allocations';
        return component($component, compact('title'));
    }

    /**
    * @uri  /xadmin/allocations/edit?id=$id
    * @throw  NotFoundHttpException
    * @return  View
    */
    public function edit (Request $req) {
        $id = $req->id;
        $entry = Allocation::find($id);

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        /**
        * @var  Allocation $entry
        */

        $title = 'Edit';
        $component = 'AllocationForm';


        return component($component, compact('title', 'entry'));
    }

    /**
    * @uri  /xadmin/allocations/remove
    * @return  array
    */
    public function remove(Request $req) {
        $id = $req->id;
        $entry = Allocation::find($id);

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
    * @uri  /xadmin/allocations/save
    * @return  array
    */
    public function save(Request $req) {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');

        $rules = [
    'title' => 'max:191',
    'total_school' => 'numeric',
    'total_course' => 'numeric',
    'total_unit' => 'numeric',
    'status' => 'numeric',
];

        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }

        /**
        * @var  Allocation $entry
        */
        if (isset($data['id'])) {
            $entry = Allocation::find($data['id']);
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
            $entry = new Allocation();
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
        $entry = Allocation::find($id);

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
    * @uri  /xadmin/allocations/data
    * @return  array
    */
    public function data(Request $req) {
        $query = Allocation::query()->orderBy('id', 'desc');

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
                            'title' => ['A', 'title'],
                            'total_school' => ['B', 'total_school'],
                            'total_course' => ['C', 'total_course'],
                            'total_unit' => ['D', 'total_unit'],
                            'status' => ['E', 'status'],
                            ];

        $query = Allocation::query()->orderBy('id', 'desc');

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
