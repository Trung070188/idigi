<?php

namespace App\Http\Controllers\Admin;


use App\Exports\DeviceErrorExport;
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
        $entry = Plan::with(['lessons','planLesson'])->find($id);
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
        foreach ($entry->lessons as $lesson)
        {

            $lessonIds[]=$lesson->id;
        }



        $devices=UserDevice::query()->with(['users'])->where('plan_id','=',$entry->id)->orderBy('created_at','ASC')->get();

       $data=[];
        foreach ($devices as $device)
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
                    'secret_key'=>$device->secret_key,
                    'reason'=>$device->reason,
                    'created_at'=>$device->created_at,
                    'updated_at'=>$device->updated_at,
                    'roleName'=>$roleName,
                ];


        }
        $fileZipLessons=ZipPlanLesson::query()->with(['package_lessons'])->where('plan_id','=',$entry->id)->get();

        foreach ($fileZipLessons as $fileZipLesson)
        {
                $url[]=[
                    'url'=>$fileZipLesson->url,
                    'packagePlanId'=>@$fileZipLesson->package_lessons->package_plan_id,
                    'planId'=>@$fileZipLesson->package_lessons->plan_id
                ];
        }
        $jsonData = [
            'lessonIds'=>$lessonIds,
            'idRoleIt' => $idRoleIt,
            'entry'=>$entry,
            'roleIt'=>$roleIt,
            'data'=>$data,
            'url'=>$url
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
//            PackageLesson::create(['plan_id'=>$entry->id,'total_lesson']);

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
                    ];

                }

            }

        }
    }
    public function import(Request $req)
    {
        $dataImport=$req->all();
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
            $user=Auth::user();
            if(@$dataImport['fileImport'] )
            {
                foreach ($dataImport['fileImport'] as $import)
                {
                    $device=new UserDevice();
                    $device->device_name=$import['device_name'];
                    $device->type=$import['type'];
                    $device->device_uid=$import['device_uid'];
                    $device->status=$import['status'];
                    $device->secret_key=Str::random(10);
                    $device->plan_id=$entry->id;
                    $device->user_id=$dataImport['idRoleIt'];
                    $device->save();


                }
                return [
                    'code' => 0,
                    'message' => 'Đã cập nhật',
                ];
            }
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
               PlanLesson::where('plan_id',$entry->id)->delete();
                foreach ($dataLesson['lessonIds'] as $lesson)
                {
                    PlanLesson::create(['plan_id'=>$entry->id,'lesson_id'=>$lesson]);
                }
                $stringLesson=implode(",",$dataLesson['lessonIds']);
                $user=Auth::user();
                ZipPlanLesson::where('plan_id',$entry->id)->delete();
                ZipPlanLesson::create(['user_id'=>$user->id,'plan_id'=>$entry->id,'lesson_ids'=>$stringLesson]);
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
//    public function notification()
//    {
//        $planLessons = ZipPlanLesson::where('status', NULL)->with('plan')->get();
//        $infos = [];
//
//
//        foreach ($planLessons as $planLesson){
//            $lessonIds = explode(',', $planLesson->lesson_ids);
//            $planLesson->status= 'inprogress';
//            $planLesson->save();
//            $info = [
//                'user_id' => $planLesson->user_id,
//                'ip_address' => NULL,
//                'user_agent' => NULL,
//                'device_uid' => NULL,
//                'lesson_ids' =>  $lessonIds,
//                'plan_id' =>  $planLesson->id,
//                'secret_key' =>  @$planLesson->plan->secret_key
//            ];
//
//            $infos[] = $info;
//        }
//
//        foreach ($infos as $info){
//            $url = $this->downloadLesson($info);
//            $notify = new Notification();
//            $notify->status = 'new';
//            $notify->content = "File download";
//            $notify->channel = 'inapp';
//            $notify->user_id = $info['user_id'];
//            $notify->url = $url;
//            $notify->title = 'File download đã hoàn thành';
//            $notify->save();
//        }
//    }
//    public function downloadLesson($info)
//    {
//        ob_get_clean();
//
//        $password = env('SECRET_KEY') . '_' .  $info['secret_key'];
//
//        $y = date('Y');
//        $m = date('m');
//        $d = date('d');
//        $dir = "files/downloads/{$y}/{$m}/{$d}/{$info['user_id']}";
//
//        if (!is_dir(public_path($dir))) {
//            mkdir(public_path($dir), 0755, true);
//        }
//
//        $lessons = Lesson::whereIn('id', $info['lesson_ids'])
//            ->with(['inventories'])->get();
//
//
//        $filenameAll = uniqid(time() . rand(10, 100));
//        $pathZipAll = $dir . '/all_lessons_' . $filenameAll . '.zip';
//        $zipFileAll = public_path($pathZipAll);
//        $zipAll = new \ZipArchive();
//        $zipAll->open($zipFileAll, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
//
//        $lessonLog = DownloadLessonLog::create([
//            'user_id' => $info['user_id'],
//            'ip_address' => $info['ip_address'],
//            'user_agent' => $info['user_agent'],
//            'device_uid' => $info['device_uid'],
//            'lesson_ids' => implode(',', $info['lesson_ids']),
//            'download_at' => Carbon::now(),
//        ]);
//
//        foreach ($lessons as $key => $lesson) {
//            $filename = uniqid(time() . rand(10, 100));
//            $name = explode(':', $lesson->name);
//            $zip_file = public_path($dir . '/' . $name[0] . '.zip');
//            $zip = new \ZipArchive();
//            $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
//            $structure = json_decode($lesson->structure, true);
//
//
//            if ($lesson->inventories) {
//                foreach ($lesson->inventories as $inventory) {
//                    $icon = 'Icons/' . basename(public_path($inventory->image));
//                    $link = basename(public_path($inventory->virtual_path));
//
//                    if (file_exists(public_path($inventory->image)) && is_file(public_path($inventory->image))) {
//                        $zip->addFile(public_path($inventory->image), $icon);
//                        $zip->setEncryptionName($icon, \ZipArchive::EM_AES_256, $password);
//                    }
//                    if (file_exists(public_path($inventory->virtual_path)) && is_file(public_path($inventory->virtual_path))) {
//                        $zip->addFile(public_path($inventory->virtual_path), $link);
//                        $zip->setEncryptionName($link, \ZipArchive::EM_AES_256, $password);
//                    }
//
//                    $dataDownloadInventory = [
//                        'user_id' => $info['user_id'],
//                        'ip_address' => $info['ip_address'],
//                        'user_agent' => $info['user_agent'],
//                        'device_uid' => $info['device_uid'],
//                        'lesson_id' => $lesson->id,
//                        'download_at' => Carbon::now(),
//                        'type' => 'cms',
//                        'inventory_id' => $inventory->id
//                    ];
//                    UpdateDownloadInventory::dispatch($dataDownloadInventory);
//
//                }
//            }
//
//            Storage::put($dir . '/lesson_detail' . $filename . '.txt', json_encode($structure));
//
//            $zip->addFile(storage_path('app/' . $dir . '/lesson_detail' . $filename . '.txt'), 'lesson_detail.txt');
//            $zip->setEncryptionName('lesson_detail.txt', \ZipArchive::EM_AES_256, $password);
//            $zip->close();
//
//            $dataLessonFile = [
//                'download_lesson_log_id' => $lessonLog->id,
//                'path' => $zip_file,
//                'is_main' => 0,
//                'is_deleted_file' => 0
//            ];
//
//            UpdateDownloadLessonFile::dispatch($dataLessonFile);
//
//            $zipAll->addFile($zip_file, $name[0] . '.zip');
//            $zipAll->setEncryptionName('/' . $name[0] . '.zip', \ZipArchive::EM_AES_256, $password);
//
//        }
//
//        $zipAll->close();
//
//        $dataLessonFile = [
//            'download_lesson_log_id' => $lessonLog->id,
//            'path' => $zipFileAll,
//            'is_main' => 1,
//            'is_deleted_file' => 0
//        ];
//        UpdateDownloadLessonFile::dispatch($dataLessonFile);
//
//        ZipPlanLesson::where('id',$info['plan_id'])->update([
//            'url' => url($pathZipAll),
//            'status' => 'done',
//        ]);
//
//
//        return url($pathZipAll);
//    }
}
