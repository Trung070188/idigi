<?php

namespace App\Http\Controllers\Admin;


use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\UserDevice;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use function Sodium\randombytes_buf;


class UserDevicesController extends AdminBaseController
{
    public static $menus = [
        [
//            'name' => 'UserDevice',
//            'icon' => 'fa fa-shopping-cart',
//            'url' => '/xadmin/user_devices/index',
        ]
    ];

    /**
    * Index page
    * @uri  /xadmin/user_devices/index
    * @throw  NotFoundHttpException
    * @return  View
    */
    public function index() {
        $title = 'UserDevices';
        $component = 'User_deviceIndex';
        return component($component, compact('title'));
    }
    /**
    * Create new entry
    * @uri  /xadmin/user_devices/create
    * @throw  NotFoundHttpException
    * @return  View
    */
//    public function create (Request $req) {
//
//        $component = 'User_deviceForm';
//        $title = 'Create user_devices';
//        return component($component, compact('title'));
//    }

    /**
    * @uri  /xadmin/user_devices/edit?id=$id
    * @throw  NotFoundHttpException
    * @return  View
    */
//    public function edit (Request $req) {
//        $id = $req->id;
//        $entry = UserDevice::find($id);
//
//        if (!$entry) {
//            throw new NotFoundHttpException();
//        }
//
//        /**
//        * @var  UserDevice $entry
//        */
//
//        $title = 'Edit';
//        $component = 'User_deviceForm';
//
//
//        return component($component, compact('title', 'entry'));
//    }

    /**
    * @uri  /xadmin/user_devices/remove
    * @return  array
    */
    public function remove(Request $req) {
        $id = $req->id;
        $entry = UserDevice::find($id);

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
    * @uri  /xadmin/user_devices/save
    * @return  array
    */
    public function save(Request $request) {
        if (!$request->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }
        $data = $request->get('entry');
        $rules = [
    'device_uid' => 'required|max:45',
    'device_name' => 'required|max:45',
];

        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }

        /**
        * @var  UserDevice $entry
        */
        $entry = new UserDevice();
            $entry->device_uid=$request->input('device_uid');
            $entry->device_name=$request->input('device_name');
            $entry->user_id=auth()->id();
            $entry->secret_key=(Str::random(10));
            $entry->status=0;
            $entry->fill($data);
            $entry->save();

            return [
                'code' => 0,
                'message' => 'Đã thêm',
                'id' => $entry->id,
            ];
        }
    public function savesend(Request $request) {
        if (!$request->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }
        $data = $request->get('entry');
        $rules = [
            'device_uid' => 'required|max:45',
            'device_name' => 'required|max:45',
        ];

        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }

        /**
         * @var  UserDevice $entry
         */
        $entry = new UserDevice();
        $entry->device_uid=$request->input('device_uid');
        $entry->device_name=$request->input('device_name');
        $entry->user_id=auth()->id();
        $entry->secret_key=(Str::random(10));
        $entry->status=1;
        $entry->fill($data);
        $entry->save();
        return [
            'code' => 0,
            'message' => 'Đã thêm',
            'id' => $entry->id
        ];
    }

//    }

    /**
    * @param  Request $req
    */
    public function toggleStatus(Request $req)
    {
        $id = $req->get('id');
        $entry = UserDevice::find($id);

        if (!$id) {
            return [
                'code' => 404,
                'message' => 'Not Found'
            ];
        }

        $entry->status = $req->status ? 0 : 1;
        $entry->save();

        return [
            'code' => 200,
            'message' => 'Đã gửi yêu cầu'
        ];
    }

    /**
    * Ajax data for index page
    * @uri  /xadmin/user_devices/data
    * @return  array
    */
    public function data(Request $req) {
        $count_user=auth()->id();
        $query = UserDevice::query()
            ->where('user_id',$count_user)
            ->orderBy('id', 'desc');

        if ($req->keyword) {
            //$query->where('title', 'LIKE', '%' . $req->keyword. '%');
        }

        $query->createdIn($req->created);
        $entries = $query->paginate();
//            $user_id=auth()->id();
//        $count_user=UserDevice::where('user_id',$user_id)->count();

        return [
            'code' => 0,
//            'count_user'=>$count_user,
            'data' => $entries->items(),
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
            ]
        ];
    }

    public function export() {
                $keys = [
                            'device_uid' => ['A', 'device_uid'],
                            'device_name' => ['B', 'device_name'],
                            'user_id' => ['C', 'user_id'],
                            ];

        $query = UserDevice::query()->orderBy('id', 'desc');

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

    public function getDeviceByUser(){
        $user = Auth::user();
        $devices = UserDevice::where('user_id', $user->id)->select(['device_name', 'id'])->get()->toArray();

        return $devices;

    }

    public function generateToken(Request  $request){
        $user = Auth::user();
        $device = UserDevice::where('user_id', $user->id)
            ->where('id', $request->device_id)
            ->where('status', 2)->first();


        if($device){
            $payload = [
                'user_id' => $user->id,
                'device_uid' =>$device->device_uid,
                'device_name' =>$device->device_name,
                'secret_key' =>$device->secret_key,
                'expired' => strtotime(Carbon::now()->addHours(10))
            ];
            return ['status' => 1, 'token' =>  jwtToken($payload)];

        }

        return  ['status' => 0, 'token' =>  'Error'];
    }
}
