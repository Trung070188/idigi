<?php

namespace App\Http\Controllers\Admin;


use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\School;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class SchoolsController extends AdminBaseController
{
    public static $menus = [
        [
            'name' => 'School',
            'icon' => 'fa fa-shopping-cart',
            'url' => '/xadmin/schools/index',
        ]
    ];

    /**
     * Index page
     * @uri  /xadmin/schools/index
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function index()
    {
        $title = 'School';
        $component = 'SchoolIndex';
        return component($component, compact('title'));
    }

    /**
     * Create new entry
     * @uri  /xadmin/schools/create
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function create(Request $req)
    {
        $component = 'SchoolForm';
        $title = 'Create schools';
        return component($component, compact('title'));
    }
    public function teacher_list(Request $req)
    {
        $title = 'TeacherList';
        $component = 'TeacherList';
        $id = $req->id;
        $entry = School::find($id);
        $query = User::query()->where('school_id','=',$entry->id);
        $query->whereHas('roles', function ($q) use ($req) {
            $q->where('role_name', 'Teacher');
        });
        $limit = 25;
        if($req->limit){
            $limit = $req->limit;
        }
        $teachers=$query->paginate($limit);

     foreach ($teachers as $teacher)
     {
        @$device_teacher=($teacher->user_devices);
     }
        $jsonData = [
            'data' => $teachers->items(),
            'entry'=>$entry,
            @'device_teacher'=>@$device_teacher,
            'paginate' => [
                'currentPage' => $teachers->currentPage(),
                'lastPage' => $teachers->lastPage(),
                'totalRecord' => $teachers->count()

            ]
        ];
        if (!$entry) {
            throw new NotFoundHttpException();
        }
        /**
         * @var  School $entry
         */

        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }

    /**
     * @uri  /xadmin/schools/edit?id=$id
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function edit(Request $req)
    {
        $id = $req->id;
        $entry = School::find($id);

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        /**
         * @var  School $entry
         */

        $title = 'Edit';
        $component = 'SchoolForm';


        return component($component, compact('title', 'entry'));
    }

    /**
     * @uri  /xadmin/schools/remove
     * @return  array
     */
    public function remove(Request $req)
    {
        $id = $req->id;
        $entry = School::find($id);

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
     * @uri  /xadmin/schools/save
     * @return  array
     */
    public function save(Request $req)
    {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');

        $rules = [
            'school_name' => 'required|max:45',
            'school_address' => 'required|max:255',
            'school_email' => 'required|max:45|email',
            'school_phone' => 'required|max:45',
            'license_to' => 'required',
            'license_info' => 'max:1000',
            'number_of_users' => 'required|integer|min:1',
            'devices_per_user' => 'required|integer|min:1',
        ];

        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }

        /**
         * @var  School $entry
         */
        if (isset($data['id'])) {
            $entry = School::find($data['id']);
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
            $entry = new School();
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
        $entry = School::find($id);

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
     * @uri  /xadmin/schools/data
     * @return  array
     */
    public function data(Request $req)
    {
        $query = School::query()->orderBy('id', 'desc');

        if ($req->keyword) {
            $query->where('school_name', 'LIKE', '%' . $req->keyword. '%');
        }

        if ($req->school_name) {
            $query->where('school_name', 'LIKE', '%' . $req->school_name. '%');
        }

        $limit = 25;

        if($req->limit){
            $limit = $req->limit;
        }

        $entries = $query->paginate($limit);

        return [
            'code' => 0,
            'data' => $entries->items(),
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
                'totalRecord' => $entries->count()
            ]
        ];
    }

    public function export()
    {
        $keys = [
            'school_name' => ['A', 'school_name'],
            'school_address' => ['B', 'school_address'],
            'school_email' => ['C', 'school_email'],
            'school_phone' => ['D', 'school_phone'],
            'license_info' => ['E', 'license_info'],
            'license_to' => ['F', 'license_to'],
            'license_state' => ['G', 'license_state'],
            'number_of_users' => ['H', 'number_of_users'],
            'devices_per_user' => ['I', 'devices_per_user'],
        ];

        $query = School::query()->orderBy('id', 'desc');

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
    public function removeAll(Request $req)
    {
        $ids = $req->ids;
        School::whereIn('id', $ids)->delete();
        return [
            'code' => 0,
            'message' => 'Đã xóa'
        ];
    }
}
