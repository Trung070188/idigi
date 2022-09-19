<?php

namespace App\Http\Controllers\Admin;


use App\Exports\DeviceErrorExport;
use App\Exports\DevicePlanExport;
use App\Imports\DeviceImport;
use App\Jobs\UpdateDownloadInventory;
use App\Jobs\UpdateDownloadLessonFile;
use App\Models\AllocationContentSchool;
use App\Models\DownloadLessonLog;
use App\Models\File;
use App\Models\Lesson;
use App\Models\Notification;
use App\Models\PackageLesson;
use App\Models\PlanLesson;
use App\Models\School;
use App\Models\SchoolPlan;
use App\Models\UserDevice;
use App\Models\ZipPlanLesson;
use Carbon\Carbon;
use Facade\Ignition\Support\Packagist\Package;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
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
        $entry = Plan::with(['lessons','planLesson','schools','package_lessons'])->find($id);
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
        $lessonIds=[];
        $packagePlan=[];
        $devices=UserDevice::query()->with(['users'])->where('plan_id','=',$entry->id)->orderBy('created_at','ASC')->get();
       $data=[];
        $fileZipLessons=ZipPlanLesson::where('plan_id','=',$entry->id)->get();
        foreach ($fileZipLessons as $fileZipLesson)
        {
            $url[]=$fileZipLesson;
        }

        $schools=School::query()->orderBy('id','desc')->get();
        $schoolPlan=[];
        $nameSchool=[];

        $lengthDevicePlan=0;
        if(@$entry->schools)
        {
            foreach ($entry->schools as $school)
            {

                $lengthDevice=[];

                foreach ($devices as $device)
                {
                    if($device->school_id==$school->id)
                    {
                        $user=Auth::user();

                        $role=$device->users->roles;
                        foreach ($role as $roless)
                        {
                            $roleName=$roless->role_name;
                        }
                        $data[]=[
                            'id'=>$device->id,
                            'device_uid'=>$device->device_uid,
                            'device_name'=>$device->device_name,
                            'user_id'=>$device->user_id,
                            'plan_id'=>$device->plan_id,
                            'type'=>$device->type,
                            'status'=>$device->status,
                            'school_id'=>$device->school_id,
                            'secret_key'=>$device->secret_key,
                            'reason'=>$device->reason,
                            'created_at'=>$device->created_at,
                            'updated_at'=>$device->updated_at,
                            'roleName'=>$roleName,
                        ];

                    }
                if($school->id==$device->school_id)
                {
                    $lengthDevice[]=$device;
                }
                }
                $schoolPlan[]=$school->id;
                $nameSchool[]=[
                    'id'=>$school->id,
                    'school_name'=>$school->label,
                    'lengthDevicePlan'=>$lengthDevice,

                ];
            }

        }
        $packageLessonPlan=PackageLesson::Where('plan_id','=',$entry->id)->get();
        foreach ($entry->package_lessons  as $packageLesson)
        {
            $arrays=explode(',',$packageLesson->lesson_ids);
            $lessonIdArr = [];
            foreach ($arrays as $item)
            {
                if($item)
                {
                    $lessonIdArr[] = (int)$item;
                }
            }
            $lessonIds[]=[
                'package_id'=>$packageLesson->id,
                'plan_id'=>$packageLesson->plan_id,
                'lessonIds'=>$lessonIdArr,
            ];

        }

        $jsonData = [
            'lessonIds'=>$lessonIds,
            'idRoleIt' => $idRoleIt,
            'entry'=>$entry,
            'roleIt'=>$roleIt,
            'data'=>$data,
            'url'=>@$url,
            'schools'=>$schools,
            'schoolPlan'=>@$schoolPlan,
            'nameSchool'=>@$nameSchool,
            'packagePlan'=>@$packagePlan,
            'packageLessonPlan'=>@$packageLessonPlan
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
            if(@$dataRole['schoolPlan'])
            {
                SchoolPlan::where('plan_id',$entry->id)->delete();
                foreach ($dataRole['schoolPlan'] as $school)
                {
                    SchoolPlan::create(['school_id'=>$school,'plan_id'=>$entry->id]);
                }
            }

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
            $entry->secret_key=Str::random(10);
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
            if(@$dataRole['deviceName'])
            {
                $device->device_name=$dataRole['deviceName'];

            }
            try {
                $decoded = JWT::decode($dataRole['deviceUid'], new Key(env('SECRET_KEY'), 'HS256'));
                $device->device_uid=$decoded->device_uid;
            }catch (\Exception $e)
            {

            }
                $device->user_id=$dataRole['idRoleIt'];
                $device->plan_id=$entry->id;
                $device->status=2;
                $device->school_id=$dataRole['schoolId'];
                $device->secret_key=(Str::random(10));
                $device->save();
            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
                'id' => $entry->id
            ];
        }

    }

    public function validateImportDevice(Request $req)
    {
        {
            if (!$req->isMethod('POST')) {
                return ['code' => 405, 'message' => 'Method not allow'];
            }


            $rules = [

            ];

            $v = Validator::make($req->all(), $rules);

            if ($v->fails()) {
                return [
                    'code' => 2,
                    'errors' => $v->errors()
                ];
            }

            //Upload File
            $file0 = $_FILES['file_0'];
            $y = date('Y');
            $m = date('m');

            $allowed = [
                'xls', 'xlsx'
            ];


            $info = pathinfo($file0['name']);
            $extension = strtolower($info['extension']);

            if (!in_array($extension, $allowed)) {
                return [
                    'code' => 3,
                    'message' => 'Extension: ' . $extension . ' is now allowed'
                ];
            }

            $dir = public_path("uploads/excel_import/{$y}/{$m}");
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $info = pathinfo($file0['name']);
            $extension = strtolower($info['extension']);

            $hash = sha1(uniqid());
            $newFilePath = $dir . '/' . $hash . '.' . $extension;
            $ok = move_uploaded_file($file0['tmp_name'], $newFilePath);
            $newUrl = url("/uploads/excel_import/{$y}/{$m}/{$hash}.{$extension}");
            $sheets = Excel::toCollection(new DeviceImport(), "{$y}/{$m}/{$hash}.{$extension}", 'excel-import');
            $deviceLists[] = $sheets;
            $validations = [];
            $error = [];
            $code = 0;
            foreach ($deviceLists as $deviceList) {

                foreach ($deviceList as $device) {
                    foreach ($device as $key => $dev) {
                        if($key>0)
                        {
                            $item = [];
                            $item['device_name'] = $dev[0];
                            $item['type'] = $dev[1];
                            $item['device_uid'] = $dev[2];
                            try {
                                $decoded = JWT::decode( $item['device_uid'], new Key(env('SECRET_KEY'), 'HS256'));
                               $item['device_uid']=$decoded->device_uid;
                            }catch (\Exception $e)
                            {

                            }
                            $item['status'] = $dev[3];
                            $validator = Validator::make($item, [
                                'device_name' => ['required',Rule::unique('user_devices','device_name')],
                                'device_uid' => ['required',Rule::unique('user_devices','device_uid')],
                                'type' => 'required',
                                'status' => 'required',
                            ]);

                            if ($validator->fails()) {
                                $item['error'] = $validator->errors()->messages();
                                $code = 2;//Có lỗi
                            }
                            $validations[] = $item;
                        }
                    }
                }
                $fileError = [];
                $fileImport=[];
                $devicePlan=[];
                if ($code == 2) {
                    //export
                    foreach ($validations as $validation) {
                        if (@$validation['error']) {
                                    $fileError[] =[
                                        'device_name'=>$validation['device_name'],
                                        'type'=>$validation['type'],
                                        'device_uid'=>'',
                                        'status'=>$validation['status'],
                                        'error'=>$validation['error'],
                                    ];
                                }
                        else
                        {

                            $fileImport[]=$validation;
                        }
                    }
                    Excel::store(new DeviceErrorExport($fileError), "{$y}/{$m}/{$hash}.{$extension}", 'excel-export');

                    $file = new File();
                    $file->type = $file0['type'];
                    $file->hash = sha1($newFilePath);
                    $file->url = $newUrl;
                    $file->is_image = 0;
                    $file->is_excel = 1;
                    $file->size = $file0['size'];
                    $file->name = $info['filename'];
                    $file->path = $newFilePath;
                    $file->extension = $extension;
                    $file->save();
                    return [
                        'code'=>2,
                        'deviceError'=>$fileError,
                        'fileImport'=>$fileImport,
                        'fileError' => url("exports/{$y}/{$m}/{$hash}.{$extension}"),

                    ];

                }
                else{
                        return [
                            'code'=>1,
                            'fileImport'=>$validations,
                            'exportDevicePlan'=>url("exports/{$y}/{$m}/{$hash}.{$extension}"),
                        ];

                }

            }

        }
    }
    public function import(Request $req)
    {
        $dataImport = $req->all();
        $data = $req->get('entry');

        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }


        $rules = [
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
            $user = Auth::user();
            $devicePlan = [];
            if (@$dataImport['fileImport']) {
                foreach ($dataImport['fileImport'] as $import) {
                    $device = new UserDevice();
                    $device->device_name = $import['device_name'];
                    $device->type = $import['type'];
                    $device->device_uid = $import['device_uid'];
                    $device->status = 2;
                    $device->secret_key = Str::random(10);
                    $device->plan_id = $entry->id;
                    $device->school_id = $dataImport['schoolId'];
                    $device->user_id = $dataImport['idRoleIt'];
                    $device->save();
                    $dataPlanExport = [];
                    $devices = UserDevice::where('user_id', $dataImport['idRoleIt'])->Where
                    ('plan_id', '=', $entry->id)
                        ->where('status', 2)
                        ->get();
                    $school = School::where('id', $dataImport['schoolId'])->first();

                    if ($school) {
                        $expired = $school->license_to;
                    }

                    if (!$expired) {
                        $expired = Carbon::now()->addHours(-10);
                    }
                    $apiPlan = [];
                    {
                        $apiPlan[] = [
                            'id' => $entry->id,
                            'name' => $entry->name,
                            'secret_key' => $entry->secret_key,
                        ];
                    }
                    $payload = [];
                    foreach ($devices as $device) {
                        $payload [] = [
                            'username'=>$user->username,
                            'full_name'=>$user->full_name,
                            'plan' => $apiPlan,
                            'user_id' => $user->id,
                            'device_uid' => $device->device_uid,
                            'device_name' => $device->device_name,
                            'secret_key' => $device->secret_key,
                            'create_time' => Carbon::now()->timestamp,
                            'expired' => strtotime($expired)
                        ];

                    }
                    $school = School::where('id', $dataImport['schoolId'])->first();

                    foreach ($payload as $pay) {

                        $jwt = JWT::encode($pay, env('SECRET_KEY'), 'HS256');
                        $dataPlanExport[] = [
                            'device_name' => $pay['device_name'],
                            'device_uid' => $pay['device_uid'],
                            'school_name' => $school->label,
                            'code' => $jwt
                        ];
                    }


                }
                $y = date('Y');
                $m = date('m');
                $hash = sha1(uniqid());

            }
            Excel::store(new DevicePlanExport($dataPlanExport), "{$y}/{$m}/{$hash}.xlsx", 'excel-export');
            Plan::updateOrCreate(
                [
                    'id' => $entry->id,
                ],
                [
                    'export_devices' => url("exports/{$y}/{$m}/{$hash}.xlsx")
                ]
            );

            return [
              'code'=>0,
              'message'=>'Đã cập nhật '
            ];

        }
    }

    public function exportDevice(Request $req)
    {
        $dataImport = $req->all();
        $data = $req->get('entry');

        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }


        $rules = [
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
            $user = Auth::user();
            $exportDevice=[];
            $payload = [];

            if (@$dataImport['dataDevice']) {
                foreach ($dataImport['dataDevice'] as $import) {

                    if($import['school_id']==$dataImport['idListDevice'] && $import['plan_id']==$entry->id)
                    {
                        $exportDevice[]=$import;
                    }

                    $school = School::where('id', $dataImport['schoolId'])->first();

                    if ($school) {
                        $expired = $school->license_to;
                    }

                    if (!$expired) {
                        $expired = Carbon::now()->addHours(-10);
                    }
                    $apiPlan = [];
                    {
                        $apiPlan[] = [
                            'id' => $entry->id,
                            'name' => $entry->name,
                            'secret_key' => $entry->secret_key,
                        ];
                    }
                    if($import['school_id']==$dataImport['idListDevice'] && $import['plan_id']==$entry->id)
                    {
                        $payload [] = [
                            'plan' => $apiPlan,
                            'user_id' => $user->id,
                            'device_uid' => $import['device_uid'],
                            'device_name' => $import['device_name'],
                            'secret_key' => $import['secret_key'],
                            'create_time' => Carbon::now()->timestamp,
                            'expired' => strtotime($expired)
                        ];
                    }
                    $school = School::where('id', $dataImport['schoolId'])->first();
                    $dataPlanExport=[];

                    foreach ($payload as $pay) {

                        $jwt = JWT::encode($pay, env('SECRET_KEY'), 'HS256');
                        $dataPlanExport[] = [
                            'device_name' => $pay['device_name'],
                            'device_uid' => $pay['device_uid'],
                            'school_name' => $school->label,
                            'code' => $jwt
                        ];
                    }


                }
                $y = date('Y');
                $m = date('m');
                $hash = sha1(uniqid());

            }
            Excel::store(new DevicePlanExport($dataPlanExport), "{$y}/{$m}/{$hash}.xlsx", 'excel-export');
            return [
              'code'=>0,
              'url'=>url("exports/{$y}/{$m}/{$hash}.xlsx"),
            ];

        }

    }
    public function planLesson(Request $req)
    {
        $dataLesson=$req->all();
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

            if(@$dataLesson['lessonIds'])
            {
               PlanLesson::where('package_id',$dataLesson['package'])->delete();
                foreach ($dataLesson['lessonIds'] as $lesson)
                {
                    if($lesson['package_id']==$dataLesson['package'])
                    {
                        foreach ($lesson['lessonIds'] as $lessonId)
                        {
                            {
                                PlanLesson::create(['plan_id'=>$entry->id,'lesson_id'=>$lessonId,'package_id'=>$dataLesson['package']]);

                            }
                        }
                        $stringLesson=implode(",",$lesson['lessonIds']);
                        PackageLesson::updateorCreate(
                            [
                                'id'=>$dataLesson['package']
                            ],
                            [
                                'lesson_ids'=>$stringLesson,
                                'status'=>'done'
                            ]
                        );
                    }

                }


                $entry->status='Ready';
                $entry->save();
                ZipPlanLesson::where('package_id',$dataLesson['package'])->delete();
                ZipPlanLesson::create(['package_id'=>$dataLesson['package'],'plan_id'=>$entry->id]);
            }
            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
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
                'totalRecord' => $entries->count(),
            ]
        ];
    }
    public function dataLesson(Request $req)
    {
        $user = Auth::user();
        $unitIds = [];
        $schoolId = $user->school_id;
        $isSuperAdmin = 0;
        $isLesson = 0;


        foreach ($user->roles as $role) {
            if ($role->role_name == 'Teacher') {
                if ($user->user_units) {
                    foreach ($user->user_units as $unit) {
                        $unitIds[] = $unit->unit_id;
                    }
                }

            }
            if ($role->role_name == 'School Admin') {
                $contents = AllocationContentSchool::where('school_id', $schoolId)
                    ->with(['allocation_content', 'allocation_content.units'])
                    ->get();
                foreach ($contents as $content) {
                    if (@$content->allocation_content->units) {
                        foreach ($content->allocation_content->units as $unit) {
                            $unitIds[] = $unit->id;
                        }

                    }
                }
            }

            if ($role->role_name == 'Super Administrator') {
                $isSuperAdmin = 1;
            }

        }

        $query = Lesson::query()->orderBy('id', 'ASC');


//        $query->whereHas('planLesson', function ($q) use ($req) {
//            $q->where('lesson_id','=',);
//
//        });

        if ($req->keyword) {
            $query->where('name', 'LIKE', '%' . $req->keyword . '%');
        }
        if ($req->name) {
            $query->where('name', $req->name);
        }
        if ($req->subject) {
            $query->where('subject', $req->subject);

        }

        if ($req->grade) {
            $query->where('grade', $req->grade);
        }

        if ($req->enabled != '') {
            $query->where('enabled', $req->enabled);
        }

        $query->createdIn($req->created);

        $limit = 25;

        if ($req->limit) {
            $limit = $req->limit;
        }

        $entries = $query->paginate($limit);

        return [
            'code' => 0,
            'data' => $entries->items(),
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
                'totalRecord' => $query->count(),
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
    public function deleteLesson(Request $req)
    {
        $data=$req->all();
        PlanLesson::where(['plan_id'=>$data['entry']['id']])->delete();
        foreach($data['lessons'] as $lesson)
        {
            PlanLesson::create(['plan_id'=>$data['entry']['id'],'lesson_id'=>$lesson]);

        }
        return [
            'code' => 0,
            'message' => 'Đã xóa'
        ];

    }
    public function addPackageLesson(Request $req)
    {
        $dataLesson=$req->all();
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');

        $rules = [
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
           PackageLesson::create(['plan_id'=>$entry->id]);
            ZipPlanLesson::create(['plan_id'=>$entry->id]);
            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
            ];
        }

    }
    public function downloadLesson(Request $req)
    {
        $dataLesson=$req->all();
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');

        $rules = [
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
            if(@$dataLesson['lessonIds'])
            {
                ZipPlanLesson::where('package_id',$dataLesson['package'])->delete();
               foreach ($dataLesson['lessonIds'] as $lesson)
               {
                   if($lesson['package_id']==$dataLesson['package'])
                   {
                       $stringLesson=implode(",",$lesson['lessonIds']);
                       $user=Auth::user();
                       ZipPlanLesson::create(['user_id'=>$user->id,'plan_id'=>$entry->id,'lesson_ids'=>$stringLesson,'package_id'=>$dataLesson['package']]);
                       $entry->status='Packaging';
                       $entry->save();

                   }
               }

            }
            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
            ];
        }

    }

    }

