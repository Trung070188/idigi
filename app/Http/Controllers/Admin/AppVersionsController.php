<?php

namespace App\Http\Controllers\Admin;

use App\Models\DownloadAppLog;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\AppVersion;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;



class AppVersionsController extends AdminBaseController
{
    public static $menus = [
        [
            'name' => 'AppVersion',
            'icon' => 'fa fa-shopping-cart',
            'url' => '/xadmin/app_versions/index',
        ]
    ];

    /**
     * Index page
     * @uri  /xadmin/app_versions/index
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function index()
    {
        $title = 'AppVersion';
        $component = 'App_versionIndex';
        $user=Auth::user();
        if(@$user->roles)
        {
            foreach($user->roles as $role)
            {
                $roleName=$role->role_name;
            }
        }
        $jsonData=[
            'roleName'=>$roleName,
        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }

    /**
     * Create new entry
     * @uri  /xadmin/app_versions/create
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function create(Request $req)
    {
        $component = 'App_versionForm';
        $title = 'Create app_versions';
        return component($component, compact('title'));
    }

    /**
     * @uri  /xadmin/app_versions/edit?id=$id
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function edit(Request $req)
    {
        $id = $req->id;
        $entry = AppVersion::find($id);

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        /**
         * @var  AppVersion $entry
         */

        $title = 'Edit';
        $component = 'App_versionForm';


        return component($component, compact('title', 'entry'));
    }

    /**
     * @uri  /xadmin/app_versions/remove
     * @return  array
     */
    public function remove(Request $req)
    {
        $id = $req->id;
        $entry = AppVersion::find($id);

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
     * @uri  /xadmin/app_versions/save
     * @return  array
     */
    public function save(Request $req)
    {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }


        $rules = [
//            'name' => 'required|unique:app_versions,name',
            'type' => 'required',
            'file_0' => 'required',
//            'release_date' => 'required',
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
        $file1=$_FILES['file1_0'];

        $fileExe = $this->uploadFile($file0);
        $fileUpdate = $this->uploadFile($file1);

        $data = [
            'is_default'=>$req->is_default,
//            'name' =>$req->name,
            'path' =>$fileExe['path'],
            'path_updated'=>$fileUpdate['path'],
            'url' => $fileExe['url'],
            'url_updated'=>$fileUpdate['url'],
            'type' => $req->type,
//            'release_date' => $req->release_date,
            'version'=>$req->version,
            'release_note'=>$req->release_note,
        ];

        $entry = new AppVersion();
        $entry->fill($data);
       if($entry->is_default=='false')
       {
        //    dd(1);
           $entry->is_default=0;
           $entry->save();

        return [
            'code' => 0,
            'message' => 'Đã thêm',
            'id' => $entry->id
        ];
       }
    AppVersion::where('is_default','=',1)->where('type',$entry->type)->update(['is_default' => 0]);
    $entry->is_default=1;
    $entry->save();
        return [
            'code' => 0,
            'message' => 'Đã thêm',
            'id' => $entry->id
        ];
    }

    protected function uploadFile($file){
        $y = date('Y');
        $m = date('m');

        $dir = public_path("files/app_version/{$y}/{$m}");

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $info = pathinfo($file['name']);
        $extension = strtolower($info['extension']);

        $hash = sha1(uniqid());
        $newFilePath = $dir.'/'.$hash.'.'.$extension;

        move_uploaded_file($file['tmp_name'], $newFilePath);
        $newUrl = url("/files/app_version/{$y}/{$m}/{$hash}.{$extension}");

        return [
            'url' => $newUrl,
            'path' => $newFilePath
        ];
    }

    /**
     * @param Request $req
     */
    public function toggleStatus(Request $req)
    {
        $id = $req->get('id');
        $entry = AppVersion::find($id);

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
     * @uri  /xadmin/app_versions/data
     * @return  array
     */
    public function data(Request $req)
    {
        $query = AppVersion::query()->orderBy('id', 'desc');
        if ($req->keyword) {
            //$query->where('title', 'LIKE', '%' . $req->keyword. '%');
        }
        $query->createdIn($req->created);
        $entries = $query->paginate(100);
        $appVersionsWindow=AppVersion::where('is_default',1)->where('type','Window')->first();
        $appVersionsOs=AppVersion::where('is_default',1)->where('type','OS')->first();


        return [
            'code' => 0,
            'data' => $entries->items(),
            'appVersionsWindow'=>$appVersionsWindow,
            'appVersionsOs'=>$appVersionsOs,
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
            ]
        ];
    }
    public function downloadApp(Request $req)
    {
        $appVersion=AppVersion::where('is_default','=',1)->first();
        $appId=$appVersion->id;
        $user=Auth::user();
        foreach ($user->user_devices as $device)
        {
            $device_uid=$device->device_uid;
        }
        $downloadAppLog=new DownloadAppLog();
        $downloadAppLog->user_id=$user->id;
        $downloadAppLog->user_agent=$req->userAgent();
        $downloadAppLog->ip_address=$req->getClientIp();
        $downloadAppLog->app_id=$appId;
        $downloadAppLog->device_uid=$device_uid;
        $downloadAppLog->download_at=Carbon::now();
        $downloadAppLog->save();


        return[
            'code'=>0,
            'url'=>$appVersion->url
        ];
        }

    public function setDefaultVersion(Request  $req){
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $entry = AppVersion::find($req->id);

        if($req->is_default == 1){
            AppVersion::where('is_default', 1)
                ->where('type', $entry->type)
                ->update(['is_default' => 0]);
        }
        if (!$entry) {
            return [
                'code' => 3,
                'message' => 'Không tìm thấy',
            ];
        }

        $entry->fill(['is_default' => $req->is_default]);
        $entry->save();

        return [
            'code' => 0,
            'message' => 'Đã cập nhật',
            'id' => $entry->id
        ];
    }



}
