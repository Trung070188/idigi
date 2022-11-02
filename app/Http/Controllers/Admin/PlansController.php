<?php

namespace App\Http\Controllers\Admin;


use App\Exports\DeviceErrorExport;
use App\Exports\DevicePlanExport;
use App\Exports\LessonPlanExport;
use App\Exports\PlanExport;
use App\Helpers\PermissionField;
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
use http\Env\Response;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
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
use phpDocumentor\Reflection\PseudoTypes\True_;
use phpseclib3\Crypt\Random;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
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
    public function index()
    {
        $title = 'Plan';
        $component = 'PlanIndex';
        $users=User::query()->orderby('id','desc')->get();
        $createBy=[];
        $assignTo=[];
        foreach ($users as $user)
        {
            foreach ($user->roles as $role)
            {
                if($role->role_name=='Super Administrator')
                {
                    $createBy[]=$user;
                }
                if($role->role_name=='IT')
                {
                    $assignTo[]=$user;
                }
            }
        }
        $jsonData = [
            'createBy'=>$createBy,
            'assignTo'=>$assignTo,
        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }

    /**
     * Create new entry
     * @uri  /xadmin/plans/create
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function create(Request $req)
    {
        $component = 'PlanForm';
        $title = 'Create plans';
        $users = User::query()->with(['roles'])->orderBy('id', 'ASC')->get();
        $roleIt = [];
        $auth = Auth::user();
        foreach ($auth->roles as $authRole) {
            $authNameRole = $authRole->role_name;
        }
        if ($authNameRole != 'IT') {
            foreach ($users as $user) {
                foreach ($user->roles as $role) {
                    if ($role->role_name == 'IT') {
                        $roleIt[] = $user;
                    }
                }
            }
        }
        if ($authNameRole == 'IT') {
            $roleIt = $auth;
        }
        $jsonData = [
            'authNameRole' => $authNameRole,
            'roleIt' => $roleIt,
        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }

    /**
     * @uri  /xadmin/plans/edit?id=$id
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function edit(Request $req)
    {
        $id = $req->id;
        $entry = Plan::with(['package_lessons'])->find($id);
        $users = User::query()->with(['roles'])->orderBy('id', 'ASC')->get();
        $auth = Auth::user();
        $roleIt = [];

        foreach ($auth->roles as $role) {
            $roleName = $role->role_name;
        }
        if ($roleName == 'IT') {
            $roleIt[] = $auth;
        }
        if ($roleName != 'IT') {
            foreach ($users as $user) {
                foreach ($user->roles as $role) {
                    if ($role->role_name == 'IT') {
                        $roleIt[] = $user;
                    }
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
        $idRoleIt = $entry->user_id;
        $packagePlan = [];
        $devices = UserDevice::query()->with(['users'])->where('plan_id', '=', $entry->id)->orderBy('created_at', 'ASC')->get();
        $data = [];
        $fileZipLessons = ZipPlanLesson::where('plan_id', '=', $entry->id)->get();
        $auth = Auth::user();
        foreach ($auth->roles as $role) {
            $roleAuth = $role->role_name;
        }
        foreach ($fileZipLessons as $fileZipLesson) {
            $url[] = $fileZipLesson;
        }
        foreach ($devices as $device) {
            $role = $device->users->roles;
            foreach ($role as $roless) {
                $roleName = $roless->role_name;
            }
            $data[] = [
                'id' => $device->id,
                'device_uid' => $device->device_uid,
                'device_name' => $device->device_name,
                'user_id' => $device->user_id,
                'plan_id' => $device->plan_id,
                'type' => $device->type,
                'status' => $device->status,
                'school_id' => $device->school_id,
                'secret_key' => $device->secret_key,
                'reason' => $device->reason,
                'expire_date' => Carbon::parse($device->expire_date)->format('d/m/Y'),
                'created_at' => $device->created_at,
                'updated_at' => $device->updated_at,
                'roleName' => $roleName,
            ];
        }

        $packageLessonPlan = PackageLesson::Where('plan_id', '=', $entry->id)->get();
        foreach ($entry->package_lessons as $packageLesson) {
            $arrays = explode(',', $packageLesson->lesson_ids);
            $lessonIdArr = [];
            foreach ($arrays as $item) {
                if ($item) {
                    $lessonIdArr[] = (int)$item;
                }
            }
            $lessonPackagePlans[] = [
                'name'=>$packageLesson->name,
                'package_id' => $packageLesson->id,
                'plan_id' => $packageLesson->plan_id,
                'lessonIds' => $lessonIdArr,
            ];
        }
        $user = Auth::user();
        $permissionDetail = new PermissionField();
        $permissions = $permissionDetail->permission($user);
        $permissionFields = [
            'plan_name' => $permissionDetail->havePermission('plan_name',$permissions,$user),
            'plan_due_date'=>$permissionDetail->havePermission('plan_due_date',$permissions,$user),
            'plan_description'=>$permissionDetail->havePermission('plan_description',$permissions,$user),
            'plan_assign_to_IT'=>$permissionDetail->havePermission('plan_assign_to_IT',$permissions,$user),
            'plan_expire_date'=>$permissionDetail->havePermission('plan_expire_date',$permissions,$user),
            'plan_add_device'=>$permissionDetail->havePermission('plan_add_device',$permissions,$user),
            'plan_delete_device'=>$permissionDetail->havePermission('plan_delete_device',$permissions,$user),
            'plan_export_device'=>$permissionDetail->havePermission('plan_export_device',$permissions,$user),
            'plan_add_package'=>$permissionDetail->havePermission('plan_add_package',$permissions,$user),
            'plan_delete_package'=>$permissionDetail->havePermission('plan_delete_package',$permissions,$user),
            'plan_export_plan'=>$permissionDetail->havePermission('plan_export_plan',$permissions,$user),
            'plan_delete_plan'=>$permissionDetail->havePermission('plan_delete_plan',$permissions,$user),
            'plan_import_device'=>$permissionDetail->havePermission('plan_import_device',$permissions,$user),
            'plan_remove_lesson'=>$permissionDetail->havePermission('plan_remove_lesson',$permissions,$user),
            'plan_download_package'=>$permissionDetail->havePermission('plan_download_package',$permissions,$user),
            'plan_add_lesson'=>$permissionDetail->havePermission('plan_add_lesson',$permissions,$user),
            'plan_zip_package_lesson'=>$permissionDetail->havePermission('plan_zip_package_lesson',$permissions,$user),
            'plan_delete_package_lesson'=>$permissionDetail->havePermission('plan_delete_package_lesson',$permissions,$user),
            'plan_rename_lesson_package'=>$permissionDetail->havePermission('plan_rename_lesson_package',$permissions,$user),


        ];
        $jsonData = [
            'permissionFields'=>$permissionFields,
            'roleAuth' => $roleAuth,
            'lessonPackagePlans' => @$lessonPackagePlans,
            'idRoleIt' => $idRoleIt,
            'entry' => $entry,
            'roleIt' => $roleIt,
            'data' => $data,
            'urls' => @$url,
            'packagePlan' => @$packagePlan,
            'packageLessonPlan' => @$packageLessonPlan
        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }

    /**
     * @uri  /xadmin/plans/remove
     * @return  array
     */
    public function remove(Request $req)
    {
        $id = $req->id;
        $entry = Plan::find($id);

        if (!$entry) {
            throw new NotFoundHttpException();
        }
        $entry->delete();
        PackageLesson::where('plan_id',$entry->id)->delete();
        ZipPlanLesson::where('plan_id',$entry->id)->delete();
        UserDevice::where('plan_id',$entry->id)->delete();
        return [
            'code' => 0,
            'message' => 'Đã xóa'
        ];
    }
    public function removeAllPlan(Request $req)
    {
       $dataAll=$req->all();
       Plan::whereIn('id',$dataAll['ids'])->delete();
        PackageLesson::whereIn('plan_id',$dataAll['ids'])->delete();
        ZipPlanLesson::whereIn('plan_id',$dataAll['ids'])->delete();
        UserDevice::whereIn('plan_id',$dataAll['ids'])->delete();
        return [
            'code' => 0,
            'message' => 'Đã xóa'
        ];
    }

    public function removePackageLesson(Request $req)
    {
        $dataPackage = $req->all();
        PackageLesson::where('id', $dataPackage['packageLesson'])->delete();
        ZipPlanLesson::where('package_id', $dataPackage['packageLesson'])->delete();
        return [
            'code' => 0,
            'message' => 'Đã xóa',
        ];
    }

    /**
     * @uri  /xadmin/plans/save
     * @return  array
     */
    public function save(Request $req)
    {
        $dataRole = $req->all();
        $auth = Auth::user();
        foreach ($auth->roles as $role) {
            $roleName = $role->role_name;
        }
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');
        $current = Carbon::now()->format('Y/m/d');
        $rules = [
            'name' => ['required','max:300'],
            'plan_description' => ['max:255'],
        ];
        if (!isset($data['id'])) {
            if ($roleName == 'IT') {
                if (@$dataRole['idRoleIt']) {
                    if ($dataRole['idRoleIt'] == null) {
                        $rules['idRoleIt'] = ['required'];

                    }
                }
            }
            if ($roleName != 'IT') {

                if ($dataRole['idRoleIt'] == null) {
                    $rules['idRoleIt'] = ['required'];

                }
            }
            $rules['due_at'] = ['after_or_equal:' . $current];
            $rules['expire_date'] = ['required', 'after_or_equal:' . $current];

        }
        if (isset($data['id'])) {
            $rules['expire_date'] = ['required', 'after_or_equal:' . $current];

        }
        $formatMessage = Carbon::now()->format('d/m/Y');
        $message=[
            'due_at.after_or_equal'=>'The due at must be a date after or equal to ' .$formatMessage,
            'expire_date.after_or_equal'=>'The expire date must be a date after or equal to '  .$formatMessage
        ];

        $v = Validator::make($data, $rules,$message);
        $v->after(function ($validate) use ($data)
        {
            if(@$data['due_at'])
            {
                if(@$data['expire_date'])
                {
                    if($data['expire_date']<$data['due_at'])
                    {
                        $validate->errors()->add('due_at','The due date must be a date before or equal to ' .Carbon::parse($data['expire_date'])->format('d/m/Y') .'.');

                    }
                }
            }

        });

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
            SchoolPlan::where('plan_id', $entry->id)->delete();
            if (@$dataRole['schoolPlan']) {
                foreach ($dataRole['schoolPlan'] as $school) {
                    SchoolPlan::create(['school_id' => $school, 'plan_id' => $entry->id]);
                }
            }

            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
                'id' => $entry->id
            ];
        } else {

            if ($roleName != 'IT') {
                $entry = new Plan();
                $entry->user_id = $dataRole['idRoleIt'];
                $entry->created_by = $auth->id;
                $entry->secret_key = Str::random(10);
                $entry->fill($data);
                $entry->save();
            }
            if ($roleName == 'IT') {
                $entry = new Plan();
                $entry->user_id = $auth->id;
                $entry->created_by = $auth->id;
                $entry->secret_key = Str::random(10);
                $entry->fill($data);
                $entry->save();
            }
            return [
                'code' => 0,
                'message' => 'Đã thêm',
                'id' => $entry->id
            ];
        }
    }

    public function saveDevice(Request $req)
    {
        $dataRole = $req->all();
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');


        $rules = [
//            'deviceExpireDate' => 'max:255',
//            'created_by' => 'numeric',
//            'due_at' => 'date_format:Y-m-d H:i:s',
        ];
        if(isset($data['id']))
        {

            if($dataRole['deviceName']==null)
            {
                $rules['deviceName']=['required'];
            }
        }

        $v = Validator::make($data, $rules,$dataRole);
        $v->after(function ($validate) use ($data,$dataRole)
        {
           if($dataRole['deviceUid']!=null)
           {
               $token = $dataRole['deviceUid'];
               try {
                   $decoded = JWT::decode($token, new Key(env('SECRET_KEY'), 'HS256'));
                   $dataRole['deviceUid']=$decoded->device_uid;
               }catch (\Exception $e)
               {

               }
               $existDeviceUid=UserDevice::query()->where('plan_id',$data['id'])->where('device_uid',$dataRole['deviceUid'])->first();
               if($existDeviceUid)
               {
                   $validate->errors()->add('deviceUid','The device uid has already been taken.');
               }
           }
           if($dataRole['deviceUid']==null)
           {
               $validate->errors()->add('deviceUid','The register code field is required.');
           }
            $existDeviceName=UserDevice::query()->where('plan_id',$data['id'])->where('device_name',$dataRole['deviceName'])->first();
           if($existDeviceName)
           {
               $validate->errors()->add('deviceName','The device name has already been taken.');
           }

            if($dataRole['deviceExpireDate']!=null)
            {
                if($dataRole['deviceExpireDate']>$data['expire_date'])
                {
                    $validate->errors()->add('deviceExpireDate','The expire date must be a date before or equal to ' .Carbon::parse($data['expire_date'])->format('d/m/Y') .'.');
                }
            }
        });
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
            if (@$dataRole['deviceName']) {
                $device->device_name = $dataRole['deviceName'];

            }
            try {
                $decoded = JWT::decode($dataRole['deviceUid'], new Key(env('SECRET_KEY'), 'HS256'));
                $device->device_uid = $decoded->device_uid;
            } catch (\Exception $e) {
                return [
                    'code' => 2,
                    'message'=>'Register code is invalid'
                ];

            }
            if ($dataRole['deviceExpireDate'] ==null)
            {
                $device->expire_date = $entry->expire_date;
            }
            else{
                $device->expire_date =$dataRole['deviceExpireDate'];
            }
            $device->user_id = $dataRole['idRoleIt'];
            $device->plan_id = $entry->id;
            $device->status = 2;
            $device->secret_key = (Str::random(10));
            $device->save();

            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
                'id' => $entry->id,
            ];
        }
    }

    public function validateImportDevice(Request $req)
    {
        $data = $req->all();

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
            $dayExpireDevice = (Carbon::parse($data['expire_date'])->format('d/m/Y'));
            $planId=$data['plan_id'];
            $deviceLists[] = $sheets;
            $validations = [];
            $error = [];
            $code = 0;
            foreach ($deviceLists as $deviceList) {

                foreach ($deviceList as  $device ) {
                    foreach ($device as $key => $dev) {

                        if ($key > 6 && $dev[0]!=null) {
                            $item = [];
                            $item['device_name'] = $dev[0];
                            $item['type'] = $dev[1];
                            $item['expire_date'] = $dev[2];
                            $item['device_uid'] = $dev[3];
                            if($item['device_uid']!=null)
                            {
                                try {
                                    $decoded = JWT::decode($item['device_uid'], new Key(env('SECRET_KEY'), 'HS256'));
                                    $item['device_uid'] = $decoded->device_uid;
                                }
                                catch (\Exception $e) {
                                }
                            }
                            else{
                                $item['device_uid'] = $dev[3];
                            }
                            if($item['expire_date']!=null)
                            {
                                $validator = Validator::make($item, [
                                    'device_name' => ['required'],
                                    'device_uid' => ['required'],
                                    'type' => 'required',
                                    'expire_date' => ['date_format:d/m/Y', 'before_or_equal:' . $dayExpireDevice]
                                ]);

                                $validator->after(function ($validate) use ($item,$planId){
                                    $existDeviceName=UserDevice::query()->where('plan_id',$planId)->where('device_name',$item['device_name'])->first();
                                       if($existDeviceName)
                                        {
                                            $validate->errors()->add('deviceName','The device name has already been taken.');
                                        }
                                    $existDeviceUid=UserDevice::query()->where('plan_id',$planId)->where('device_uid',$item['device_uid'])->first();
                                        if($existDeviceUid)
                                        {
                                            $validate->errors()->add('deviceUid','The device uid has already been taken.');
                                        }
                                });
                            }
                            if($item['expire_date']==null)
                            {
                                $validator = Validator::make($item, [
                                'device_name' => ['required'],
                                'device_uid' => ['required'],
                                'type' => 'required',
                                ]);
                                $validator->after(function ($validate) use ($item,$planId){
                                    $existDeviceName=UserDevice::query()->where('plan_id',$planId)->where('device_name',$item['device_name'])->first();
                                    if($existDeviceName)
                                    {
                                        $validate->errors()->add('deviceName','The device name has already been taken.');
                                    }
                                    $existDeviceUid=UserDevice::query()->where('plan_id',$planId)->where('device_uid',$item['device_uid'])->first();
                                    if($existDeviceUid)
                                    {
                                        $validate->errors()->add('deviceUid','The device uid has already been taken.');
                                    }
                                });
                            }


                            if ($validator->fails()) {
                                $item['error'] = $validator->errors()->messages();
                                $code = 2;//Có lỗi
                            }
                            $validations[] = $item;
                        }
                    }
                }
                $fileError = [];
                $fileImport = [];
                $devicePlan = [];
                if ($code == 2) {
                    //export
                    foreach ($validations as $validation) {
                        if (@$validation['error']) {
                            $fileError[] = [
                                'device_name' => $validation['device_name'],
                                'type' => $validation['type'],
                                'device_uid' => '',
                                'expire_date' => $validation['expire_date'],
                                'error' => $validation['error'],
                            ];
                        } else {

                            $fileImport[] = $validation;
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
                        'code' => 2,
                        'deviceError' => $fileError,
                        'fileImport' => $fileImport,
                        'fileError' => url("exports/{$y}/{$m}/{$hash}.{$extension}"),

                    ];

                } else {
                    return [
                        'code' => 1,
                        'fileImport' => $validations,
                        'exportDevicePlan' => url("exports/{$y}/{$m}/{$hash}.{$extension}"),
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
            if (@$dataImport['fileImport']) {
                foreach ($dataImport['fileImport'] as $import) {
                    $device = new UserDevice();
                    $device->device_name = $import['device_name'];
                    $device->type = $import['type'];
                    $device->device_uid = $import['device_uid'];
                    $device->status = 2;
                    $device->secret_key = Str::random(10);
                    $device->plan_id = $entry->id;
                    if($import['expire_date']!=null)
                    {
                        $device->expire_date = Carbon::createFromFormat('d/m/Y', $import['expire_date'])->format('Y-m-d');
                    }
                    if($import['expire_date']==null)
                    {
                        $device->expire_date = $entry->expire_date;
                    }
                    $device->user_id = $dataImport['idRoleIt'];
                    $device->save();
                }
            }
            return [
                'code' => 0,
                'message' => 'Đã cập nhật '
            ];

        }
    }

    public function exportDevice(Request $req)
    {
        ob_get_clean();

        $dataImport = $req->all();
        $data = json_decode($req->get('entry'),true);


        // if (!$req->isMethod('POST')) {
        //     return ['code' => 405, 'message' => 'Method not allow'];
        // }


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
            $exportDevice = [];
            $payload = [];
            $user = User::where('id', json_decode($dataImport['idRoleIt']))->first();
            if (@json_decode($dataImport['dataDevice'])) {
                foreach (json_decode($dataImport['dataDevice']) as $import) {
                    if ($import->plan_id == $entry->id) {
                        $exportDevice[] = $import;
                    }

//                    $apiPlan = [];
//                    {
//                        $apiPlan[] = [
//                            'id' => $entry->id,
//                            'name' => $entry->name,
//                            'secret_key' => $entry->secret_key,
//                        ];
//                    }
                    if ($import->plan_id == $entry->id) {
                        $expired = Carbon::createFromFormat('d/m/Y', $import->expire_date)->format('Y-m-d');

                        $payload [] = [
//                            'secret_key_plan' => $entry->secret_key,
                            'username' => $user->username,
                            'full_name' => $user->full_name,
//                            'plan' => $apiPlan,
                            'user_id' => json_decode($dataImport['idRoleIt']),
                            'device_uid' => $import->device_uid,
                            'device_name' => $import->device_name,
                            'secret_key' => $entry->secret_key,
                            'create_time' => Carbon::now()->timestamp,
                            'expired' => strtotime($expired),

                        ];
                    }
                    $dataPlanExport = [];
                    foreach ($payload as $pay) {
                        $jwt = JWT::encode($pay, env('SECRET_KEY'), 'HS256');
                        $dataPlanExport[] = [
                            'device_name' => $pay['device_name'],
                            'device_uid' => $pay['device_uid'],
                            'expire_date' => date('d/m/Y', $pay['expired']),
                            'code' => $jwt
                        ];
                    }
                }
            }
           return Excel::download(new DevicePlanExport($dataPlanExport), "Device_export_plan.xlsx");
        }

    }

    public function planLesson(Request $req)
    {
        $dataLesson = $req->all();
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');

        $rules = [
//            'name' => 'max:255',
//            'created_by' => 'numeric',
//            'due_at' => 'date_format:Y-m-d ',
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

            if (@$dataLesson['lessonPackagePlans']) {
                foreach ($dataLesson['lessonPackagePlans'] as $lesson) {
                        if ($lesson['package_id'] == $dataLesson['package'])
                        {
                            if($lesson['lessonIds']!=[]){
                                $stringLesson = implode(",", $lesson['lessonIds']);
                                PackageLesson::updateorCreate(
                                    [
                                        'id' => $dataLesson['package']
                                    ],
                                    [
                                        'lesson_ids' => $stringLesson,
                                        'status' => 'new'
                                    ]
                                );
                                return [
                                    'code'=>0,
                                    'message'=>'Đã cập nhật'
                                ];
                            }
                            else
                            {
                                return [
                                    'code'=>0,
                                    'message'=>'Chưa có lesson nào được thêm vào.'
                                ];
                            }

                        }

                    }
                }

            }
    }

    /**
     * @param Request $req
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
    public function data(Request $req)
    {
        $user = Auth::user();
        foreach ($user->roles as $role) {
            $roleName = $role->role_name;
        }
        if ($roleName == 'IT') {
            $query = Plan::query()->where('user_id', '=', $user->id)->orderBy('id', 'desc');

        }
        if ($roleName == 'Super Administrator') {
            $query = Plan::query()->orderBy('id', 'desc');
        }
        if ($req->keyword) {
            $query->where('name', 'LIKE', '%' . $req->keyword . '%')->orWhereHas('users',function ($q) use ($req){
                $q->where('full_name','LIKE', '%' . $req->keyword . '%');
            });

        }
        if($req->name)
        {
            $query->where('name', 'LIKE', '%' . $req->name . '%');
        }
        if($req->status)
        {
            $query->where('status', 'LIKE', '%' . $req->status . '%');
        }
        if($req->user_id)
        {
            $query->where('user_id', 'LIKE', '%' . $req->user_id . '%');
        }
        if($req->created_by)
        {
            $query->where('created_by', 'LIKE', '%' . $req->created_by . '%');
        }
        if($req->due_at)
        {
            $query->where('due_at', 'LIKE', '%' . $req->due_at . '%');
        }
        $query->createdIn($req->created);
        $limit = 25;
        if ($req->limit) {
            $limit = $req->limit;
        }
        $entries = $query->paginate($limit);
        $data = [];
        $users = User::query()->orderBy('id', 'desc')->get();
        $devices = UserDevice::query()->whereNotNull('plan_id')->get();
        foreach ($entries as $entry) {
            $lengthDevice = [];
            foreach ($devices as $device) {

                if ($entry->id == $device->plan_id) {
                    $lengthDevice[] = $device;
                }
            }
            foreach ($users as $user) {
                if ($user->id == $entry->created_by) {
                    $fullName = $user->full_name;
                }
                if ($entry->user_id == $user->id) {
                    $fullNameIt = $user->full_name;
                }
            }
           if($entry->due_at!=null)
           {
            $data[] = [
                'id' => $entry->id,
                'name' => $entry->name,
                'created_by' => $fullName,
                'assign_to' => $fullNameIt,
                'created_at' => $entry->created_at,
                'status' => $entry->status,
                'lengthDevice' => $lengthDevice,
                'expire_date' =>Carbon::parse($entry->expire_date)->format('d/m/Y'),
                'due_at' =>Carbon::parse($entry->due_at)->format('d/m/Y'),
            ];
           }
           else{
            $data[] = [
                'id' => $entry->id,
                'name' => $entry->name,
                'created_by' => $fullName,
                'assign_to' => $fullNameIt,
                'created_at' => $entry->created_at,
                'status' => $entry->status,
                'lengthDevice' => $lengthDevice,
                'expire_date' =>Carbon::parse($entry->expire_date)->format('d/m/Y'),
                'due_at' =>$entry->due_at,
            ];
           }

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

        $query = Lesson::query()->orderBy('name', 'ASC');


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

//        $query->createdIn($req->created);

     $limit = $query->count();

        if ($req->limit) {
            $limit = $req->limit;
        }

        $entries = $query->paginate($limit);

        return [
            'code' => 0,
            'data' => $entries->items(),
//            'paginate' => [
//                'currentPage' => $entries->currentPage(),
//                'lastPage' => $entries->lastPage(),
//                'totalRecord' => $query->count(),
//            ]
        ];
    }



    public function export()
    {
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
        $data = $req->all();
        $stringLesson = implode(",", $data['packageLesson']);

        PackageLesson::updateorCreate(
            [
                'id' => $data['viewPackage']

            ],
            [
                'lesson_ids' => $stringLesson,
//                    'status'=>'done'
            ]
        );
        if ($stringLesson == "") {
            PackageLesson::updateorCreate(
                [
                    'id' => $data['viewPackage']

                ],
                [
                    'lesson_ids' => $stringLesson,
                    'status' => 'new'
                ]
            );

        }

        return [
            'code' => 0,
            'message' => 'Đã xóa',
            'lesson' => $stringLesson
        ];

    }
    public function removeAllLesson(Request $req)
    {
        $dataAll=$req->all();
        $stringLessonIds=implode(",",$dataAll['ids']);
        if($stringLessonIds!="")
        {
            PackageLesson::updateorCreate(
                [
                    'id' => $dataAll['viewPackage']
                ],
                [
                    'lesson_ids' => $stringLessonIds,
                    'status'=>'new'
                ]
            );
        }
       else{
           PackageLesson::updateorCreate(
               [
                   'id' => $dataAll['viewPackage']
               ],
               [
                   'lesson_ids' => NUll,
                   'status'=>'new'
               ]
           );
       }
        return [
            'code' => 0,
            'message' => 'Đã xóa',
        ];

    }

    public function addPackageLesson(Request $req)
    {
        $dataLesson = $req->all();
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }
        $data = $req->get('entry');
        $rules = [
        ];
        if($dataLesson['packageLessonName']==null)
        {
            $rules['packageLessonName']=['required'];
        }

        $message=[
            'packageLessonName.required'=>'The package lesson name field is required.',
            ];
        $v = Validator::make($data, $rules,$message,$dataLesson);
        $v->after(function ($validate) use ($dataLesson){
               if(strlen($dataLesson['packageLessonName'])>50)
                {
                    $validate->errors()->add('packageLessonName','The package lessson name may not be greater than 50 characters.');
                }

        });

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
            if($dataLesson['tabLessonContent'])
            {
                PackageLesson::updateOrCreate(
                    [
                        'id'=>$dataLesson['tabLessonContent']
                    ],
                    [
                        'name'=>$dataLesson['packageLessonName']
                    ]
                );
            }
           else
            {
                $package= PackageLesson::create(['plan_id' => $entry->id, 'status' => 'new','name'=>$dataLesson['packageLessonName']]);
                ZipPlanLesson::create(['plan_id'=>$entry->id,'package_id'=>$package->id]);
            }

            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
            ];
        }
    }

    public function downloadLesson(Request $req)
    {
        $dataLesson = $req->all();
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
            if (@$dataLesson['lessonPackagePlans']) {
                ZipPlanLesson::where('package_id', $dataLesson['package'])->delete();
                foreach ($dataLesson['lessonPackagePlans'] as $lesson) {
                    if ($lesson['package_id'] == $dataLesson['package']) {
                        $stringLesson = implode(",", $lesson['lessonIds']);
                        $user = Auth::user();
                      $zipFile= ZipPlanLesson::create(['user_id' => $dataLesson['idRoleIt'], 'plan_id' => $entry->id, 'lesson_ids' => $stringLesson, 'package_id' => $dataLesson['package'], 'status' => 'inprogress']);
                        if($zipFile->status=='inprogress')
                        {
                            $entry->status='Packaging';
                        }
                        $entry->save();
                        PackageLesson::updateOrCreate(
                            [
                                'id'=>$lesson['package_id']
                            ],
                            [
                                'status'=>'done'
                            ]
                        );
                    }
                }

            }
            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
            ];
        }

    }

    public function sentSale(Request $req)
    {
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
            Plan::updateorCreate(
                [
                    'id'=>$entry->id,
                ],
                [
                    'status'=>'waiting'
                ]
            );
            if (!$entry) {
                return [
                    'code' => 3,
                    'message' => 'Không tìm thấy',
                ];
            }


            }
            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
            ];
        }
        /** Remove device all device 30/09/2022 dev: Trung */
    public function removeDeviceAll(Request $req)
    {
        $dataAll=$req->all();
        $data = $req->get('entry');
        if (isset($data['id'])) {
            $entry = Plan::find($data['id']);
            UserDevice::Where('plan_id',$entry->id)->whereIn('id',$dataAll['ids'])->delete();
            return [
                'code' => 0,
                'message' => 'Đã xóa',
            ];

        }
    }
    public function removeDevice(Request $req)
    {
        $dataAll=$req->all();
        $data = $req->get('entry');
        if (isset($data['id'])) {
            $entry = Plan::find($data['id']);
            UserDevice::Where('plan_id',$entry->id)->where('id',$dataAll['id'])->delete();
            return [
                'code' => 0,
                'message' => 'Đã xóa',
            ];

        }

    }
    public function deletePackageLesson(Request  $req)
    {
        $dataAll=$req->all();
        $data = $req->get('entry');
        if (isset($data['id'])) {
            $entry = Plan::find($data['id']);
            PackageLesson::Where('plan_id',$entry->id)->where('id',$dataAll['id'])->delete();
            ZipPlanLesson::where('plan_id',$entry->id)->where('package_id',$dataAll['id'])->delete();
            return [
                'code' => 0,
                'message' => 'Đã xóa',
            ];

        }

    }
    public function dataPackage(Request $req)
    {
            $packageLessonss=  PackageLesson::query()->orderBy('id','ASC')->get();
        $packageLesson=[];
            foreach ($packageLessonss as $packageLessons)
            {
                $arrays = explode(',', $packageLessons->lesson_ids);
                $lessonIdArr = [];
                foreach ($arrays as $item) {
                    if ($item) {
                        $lessonIdArr[] = (int)$item;
                    }
                }
                $packageLesson[]=[
                  'name'=>$packageLessons->name,
                  'id'=>$packageLessons->id,
                  'plan_id'=>$packageLessons->plan_id ,
                  'lesson_ids'=>$lessonIdArr
                ];

            }
        return [
            'data' => $packageLesson,
        ];
    }
    public function exportPlan(Request $req)
    {

        $dataAll=$req->all();

        $data = json_decode($req->get('entry'), true);



        if (isset($data['id'])) {
            $entry = Plan::find($data['id']);
            $lessons=[];
            $assignTo=User::where('id',$entry->user_id)->first();
            $dataAll['packageLessonPlan'] = json_decode($dataAll['packageLessonPlan'], true);

            if(@$dataAll['packageLessonPlan'])
            {
                foreach ( $dataAll['packageLessonPlan'] as $key => $packageLessonPlan)
                {
                    $index=$key+1;

                    $lessonsArr=Lesson::query()->whereIn('id',$packageLessonPlan['lessonIds'])->orderBy('name','ASC')->get();
                    $lessons[]=[
                        'package_name'=>'Package lesson' . ' ' .$index ,
                        'plan_name'=>$entry->name,
                        'assign_to'=>$assignTo->full_name,
                        'due_at'=>Carbon::parse($entry->due_at)->format('d/m/Y'),
                        'expire_date'=>Carbon::parse($entry->expire_date)->format('d/m/Y'),
                        'lessons'=>$lessonsArr,
                    ];
                }
            }

            $payload=[];
            $devices= json_decode($dataAll['dataDevice'], true);
            if(@$devices)
            {
                foreach ($devices as $device)
                {
                    $payload [] = [
                        'username' => $assignTo->username,
                        'full_name' => $assignTo->full_name,
                        'user_id' => $assignTo->id,
                        'device_uid' => $device['device_uid'],
                        'device_name' => $device['device_name'],
                        'secret_key' => $entry->secret_key,
                        'create_time' => Carbon::now()->timestamp,
                        'expired' => $device['expire_date'],
                    ];
                }
            }

            $dataDevicePlanExport = [];
            foreach ($payload as $pay) {
                $dataDevicePlanExport[] = [
                    'device_name' => $pay['device_name'],
                    'expire_date' => $pay['expired'],
                ];
            }

       return   Excel::download(new PlanExport($lessons,$dataDevicePlanExport), "Kế_Hoạch_Plan.xlsx");
        }

    }
    public function dataZipLessonPlan(Request $req)
    {
        $zipLessonPlan = ZipPlanLesson::query()->orderBy('id', 'ASC')->get();
        return [
            'data' => $zipLessonPlan,
        ];
    }
    public function dataDevice(Request $req)
    {
        $dataDevice=UserDevice::query()->whereNotNull('plan_id')->get();
        return[
          'data'=>$dataDevice
        ];
    }
    public function downloadTemplate() : BinaryFileResponse
    {
        return response()->download(public_path('sample/Import_device_on_PLAN_template.xlsx'));
    }
    public function generateToken(Request $req)
    {

        $device = UserDevice::where('id', $req->device_id)
                ->where('status', 2)
                ->first();
        $user=User::query()->where('id',$device->user_id)->first();
            if($device)
        {
            if($device){
                $payload = [
                    'username'=>$user->username,
                    'full_name'=>$user->full_name,
                    'user_id' => $user->id,
                    'device_uid' =>$device->device_uid,
                    'device_name' =>$device->device_name,
                    'secret_key' =>$device->secret_key,
                    'create_time' =>  Carbon::now()->timestamp,
                    'expire_date'=>strtotime($device->expire_date)
                ];
                $jwt = JWT::encode($payload, env('SECRET_KEY'), 'HS256');
                return ['status' => 1, 'token' =>  $jwt];
            }
            return  ['status' => 0, 'token' =>  'Error'];

        }

    }
}




