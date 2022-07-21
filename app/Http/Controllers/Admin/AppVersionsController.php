<?php

namespace App\Http\Controllers\Admin;


use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\AppVersion;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


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
        return component($component, compact('title'));
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
            'name' => 'required|unique:app_versions,name',
            'type' => 'required',
            'file_0' => 'required',
            'release_date' => 'required',
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

        $dir = public_path("files/app_version/{$y}/{$m}");

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $info = pathinfo($file0['name']);
        $extension = strtolower($info['extension']);

        $hash = sha1(uniqid());
        $newFilePath = $dir.'/'.$hash.'.'.$extension;
        $ok = move_uploaded_file($file0['tmp_name'], $newFilePath);
        $newUrl = url("/files/app_version/{$y}/{$m}/{$hash}.{$extension}");

        $data = [
            'name' =>$req->name,
            'path' =>$newFilePath,
            'url' => $newUrl,
            'type' => $req->type,
            'release_date' => $req->release_date
        ];

        $entry = new AppVersion();
        $entry->fill($data);
        $entry->save();

        return [
            'code' => 0,
            'message' => 'Đã thêm',
            'id' => $entry->id
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
        $users=User::with(['roles'])->orderBy('username')->get();
        foreach ($users as $user)
        {
            $userss=Auth::user();
            if($user->id==$userss->id)
            {
                foreach($userss->roles as $roless)
                {
                    $role=$roless->role_name;
                }

            }
        }

        if ($req->keyword) {
            //$query->where('title', 'LIKE', '%' . $req->keyword. '%');
        }

        $query->createdIn($req->created);


        $entries = $query->paginate(100);
        $window=AppVersion::where('type','=','window')->get();
        $macos=AppVersion::where('type','=','ios')->get();

        return [
            'code' => 0,
            'data' => $entries->items(),
            'window'=>$window,
            'macos'=>$macos,
            'role'=>$role,
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
            ]
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
    public function removeAll(Request $req)
    {
        $ids = $req->ids;
        AppVersion::whereIn('id', $ids)->where('type','=','ios')->delete();
        return [
            'code' => 0,
            'message' => 'Đã xóa'
        ];
    }
    public function windowRemoveAll(Request $req)
    {
        $ids = $req->ids;
        AppVersion::whereIn('id', $ids)->where('type','=','window')->delete();
        return [
            'code' => 0,
            'message' => 'Đã xóa'
        ];
    }


}
