<?php

namespace App\Http\Controllers\Admin;


use App\Models\UserDevice;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use phpseclib3\Crypt\Random;
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
        $users=User::query()->with(['roles'])->orderBy('id','ASC')->get();
        $roleIt=[];
        foreach($users as $user)
        {
            foreach($user->roles as $role)
            {
                if($role->role_name=='IT')
                {
                    $roleIt[]=$user;
                }
            }
        }
        $jsonData = [
            'roleIt' => $roleIt,
        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }

    /**
    * @uri  /xadmin/plans/edit?id=$id
    * @throw  NotFoundHttpException
    * @return  View
    */
    public function edit (Request $req) {
        $id = $req->id;
        $entry = Plan::find($id);
        $users=User::query()->with(['roles'])->orderBy('id','ASC')->get();
        $roleIt=[];
        foreach($users as $user)
        {
            foreach($user->roles as $role)
            {
                if($role->role_name=='IT')
                {
                    $roleIt[]=$user;
                }
            }
        }

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        /**
        * @var  Plan $entry
        */

        $title = 'Edit';
        $component = 'PlanEdit';

        $idRoleIt=$entry->user_id;
        $jsonData = [
            'idRoleIt' => $idRoleIt,
            'entry'=>$entry,
            'roleIt'=>$roleIt

        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
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
        $dataRole=$req->all();
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
            $auth=Auth::user();

            $entry = new Plan();
            $entry->user_id=$dataRole['idRoleIt'];
            $entry->created_by=$auth->id;

            $entry->fill($data);
            $entry->save();

            return [
                'code' => 0,
                'message' => 'Đã thêm',
                'id' => $entry->id
            ];
        }
    }
    public function saveDevice(Request $req) {
        $dataRole=$req->all();
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
                $device = new UserDevice();
                $device->device_name=$dataRole['deviceName'];
            try {
                $decoded = JWT::decode($dataRole['deviceUid'], new Key(env('SECRET_KEY'), 'HS256'));
                $device->device_uid=$decoded->device_uid;
            }catch (\Exception $e)
            {

            }
                $device->user_id=$dataRole['idRoleIt'];
                $device->plan_id=$entry->id;
                $device->status=2;
                $device->secret_key=(Str::random(10));
                $device->save();

            $entry->fill($data);
            $entry->save();

            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
                'id' => $entry->id
            ];
        } else {
            $auth=Auth::user();

            $entry = new Plan();
            $entry->user_id=$dataRole['idRoleIt'];
            $entry->created_by=$auth->id;

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
        $data=[];
        $users=User::query()->orderBy('id','desc')->get();

        foreach($entries as $entry)
        {
            foreach($users as $user)
            {
                if($user->id==$entry->created_by)
                {
                    $fullName=$user->full_name;
                }
                if($entry->user_id==$user->id)
                {
                    $fullNameIt=$user->full_name;
                }
            }
            $data[]=[
                'id'=>$entry->id,
                'name'=>$entry->name,
                'created_by'=>$fullName,
                'assign_to'=>$fullNameIt,
                'created_at'=>$entry->created_at,
                'due_at'=>$entry->due_at,
            ];
        }

        return [
            'code' => 0,
            'data' => $data,
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
