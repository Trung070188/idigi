<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Plan;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class PlansController extends AdminBaseController
{
    public static $menus = [
        [
            'name' => 'Plan',
            'icon' => 'fa fa-shopping-cart',
            'url' => '/xadmin/plans/index',
        ]
    ];

    /**
    * Index page
    * @uri  /xadmin/plans/index
    * @throw  NotFoundHttpException
    * @return  View
    */
    public function index() {
        $title = 'Plan';
        $component = 'PlanIndex';
        return component($component, compact('title'));
    }

    /**
    * Create new entry
    * @uri  /xadmin/plans/create
    * @throw  NotFoundHttpException
    * @return  View
    */
    public function create (Request $req) {
        $component = 'PlanForm';
        $title = 'Create plans';
        return component($component, compact('title'));
    }

    /**
    * @uri  /xadmin/plans/edit?id=$id
    * @throw  NotFoundHttpException
    * @return  View
    */
    public function edit (Request $req) {
        $id = $req->id;
        $entry = Plan::find($id);

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        /**
        * @var  Plan $entry
        */

        $title = 'Edit';
        $component = 'PlanForm';


        return component($component, compact('title', 'entry'));
    }

    /**
    * @uri  /xadmin/plans/remove
    * @return  array
    */
    public function remove(Request $req) {
        $id = $req->id;
        $entry = Plan::find($id);

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
    * @uri  /xadmin/plans/save
    * @return  array
    */
    public function save(Request $req) {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');

        $rules = [
    'name' => 'max:255',
    'created_by' => 'numeric',
    'due_at' => 'date_format:Y-m-d H:i:s',
];

        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }

        /**
        * @var  Plan $entry
        */
        if (isset($data['id'])) {
            $entry = Plan::find($data['id']);
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
            $entry = new Plan();
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
        $entry = Plan::find($id);

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
    * @uri  /xadmin/plans/data
    * @return  array
    */
    public function data(Request $req) {
        $query = Plan::query()->orderBy('id', 'desc');

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
                            'status' => ['B', 'status'],
                            'Deployed' => ['C', 'Deployed'],
                            'created_by' => ['D', 'created_by'],
                            'due_at' => ['E', 'due_at'],
                            ];

        $query = Plan::query()->orderBy('id', 'desc');

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
