<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\School;
use App\Models\UserDevice;
use App\Models\UserRole;
use App\Rules\ValiFullname;
use App\Rules\ValiUser;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Ramsey\Collection\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Validation\Rule;


class UsersController extends AdminBaseController
{
    public static $menus = [
        [
            'name' => 'User',
            'icon' => 'fa fa-shopping-cart',
            'url' => '/xadmin/users/index',
        ]
    ];

    /**
     * Index page
     * @uri  /xadmin/users/index
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function index(Request $request)
    {
        $title = 'Users';
        $component = 'UserIndex';
        $roles = Role::query()->orderBy('role_name')->get();
        $user=Auth::user();
        foreach ($user->roles as $role)
        {
            if($role->role_name=='Super Administrator')
            {
                $jsonData = [
                    'roles' => $roles
                ];
                return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
            }
            else{
                echo 'You Do Not Have Access';
            }

        }

    }
    public function index_teacher(Request $request)
    {
        $title = 'Teacher';
        $component = 'TeacherIndex';
        $roles = Role::query()->orderBy('role_name')->get();
        $user=Auth::user();
        foreach ($user->roles as $role)
        {
            if($role->role_name=='Super Administrator' || $role->role_name=='Administrator')
            {
                $jsonData = [
                    'roles' => $roles
                ];
                return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
            }
            else{
                echo 'You Do Not Have Access';
            }

        }

    }
    /**
     * Create new entry
     * @uri  /xadmin/users/create
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function create(Request $req)
    {
        $component = 'UserForm';
        $title = 'Create users';
        $roles = Role::query()->orderBy('role_name')->get();
        $user=Auth::user();
        foreach ($user->roles as $role)
        {
            if($role->role_name=='Super Administrator')
            {
                $jsonData = [
                    'roles' => $roles
                ];
                return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
            }
            else{
                echo 'You Do Not Have Access';
            }

        }

    }

    public function create_teacher(Request $req)
    {
        $component = 'TeacherCreated';
        $title = 'Create Teacher';
        $roles = Role::query()->orderBy('role_name')->get();
        $user=Auth::user();
        foreach ($user->roles as $role)
        {
            if($role->role_name=='Super Administrator'||$role->role_name=='Administrator')
            {
                $jsonData = [
                    'roles' => $roles
                ];
                return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
            }
            else{
                echo 'You Do Not Have Access';
            }
        }

    }

    /**
     * Index page
     * @uri  /xadmin/users/index
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function profile(Request $req)
    {
        $id = $req->id;
        $entry = User::with('roles')
            ->where('id', $id)->first();
        if (!$entry) {
            throw new NotFoundHttpException();
        }

        if(@$entry->fileImage){
            $entry->file_image_new= [
                'id' => @$entry->fileImage->id,
                'uri' => @$entry->fileImage->url,
                'is_image' => 1,
            ];
        }else{
            $entry->file_image_new = NULL;
        }

        $roles = Role::query()->orderBy('role_name')->get();

        $role='';
        foreach ($entry->roles as $role_id)
        {
            $role=$role_id->role_name;

        }
        /**
         * @var  User $entry
         */
        $title = 'Profile Edit';
        $component = 'ProfileForm';
        $jsonData = [
            'entry' => $entry,
            'role'=>$role,
        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }

    /**
     * @uri  /xadmin/users/edit?id=$id
     * @throw  NotFoundHttpException
     * @return  View
     */

    public function edit(Request $req)
    {

        $id = $req->id;
        $entry = User::with('roles')
            ->where('id', $id)->first();
        if (!$entry) {
            throw new NotFoundHttpException();
        }

        /**
         * @var  User $entry
         */
        $roles = Role::query()->orderBy('role_name')->get();

        $role='';
        foreach ($entry->roles as $role_id)
        {
            $role=$role_id->id;

        }
        $title = 'Edit';
        $component = 'UserEdit';
        $user=Auth::user();
        foreach ($user->roles as $role)
        {
            if($role->role_name=='Super Administrator')
            {
                $jsonData = [
                    'entry' => $entry,
                    'role'=>$role,

                ];
                return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
            }
            else{
                echo 'You Do Not Have Access';
            }
        }


    }


    public function edit_teacher(Request $req)
    {
        $id = $req->id;
        $entry = User::query()
            ->where('id', $id)->first();
        if (!$entry) {
            throw new NotFoundHttpException();
        }
        $user_device = UserDevice::query()->orderBy('id', 'DESC')->get();

        /**
         * @var  User $entry
         */

        $title = 'Edit';
        $component = 'TeacherEdit';
        $user=Auth::user();
        foreach ($user->roles as $role)
        {
            if($role->role_name=='Super Administrator'|| $role->role_name=='Administrator')
            {
                $jsonData = [
                    'entry' => $entry,
                    'user_device' => $user_device
                ];
                return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
            }
            else{
                echo 'You Do Not Have Access';
            }
        }

    }

    /**
     * @uri  /xadmin/users/remove
     * @return  array
     */
    public function remove(Request $req)
    {
        $id = $req->id;
        $entry = User::find($id);
        $entry->roles()->detach();
        if (!$entry) {
            throw new NotFoundHttpException();
        }
        $entry->delete();
        return [
            'code' => 0,
            'message' => 'Đã xóa'
        ];
    }
    public function remove_device(Request $req)
    {
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
     * @uri  /xadmin/users/save
     * @return  array
     */
    public function updatePassword(Request $req)
    {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }
        $data = $req->get('entry');
        $rules = [
            'password' => ['required'],
            'confirm_password' => ['same:password'],
        ];
        if (isset($data['id'])) {
            $rules['password'] = 'required|max:191|confirmed';
        }
        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }
        if (isset($data['id'])) {
            $entry = User::find($data['id']);

            if (!$entry) {
                return [
                    'code' => 3,
                    'message' => 'Không tìm thấy',
                ];
            }

            if(!Hash::check($data['old_password'], auth()->user()->password)){

            }
          else{
              $data['password'] = Hash::make($data['password']);
          }
            $entry->fill($data);
            $entry->save();
            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
                'id' => $entry->id,
            ];
        }

    }
    public function save_profile(Request $req)
    {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }
        $data = $req->get('entry');
        $data_role=$req->all();
        $roles = $req->roles;
        $rules = [
            'username' => ['required',new ValiUser()],
            'full_name'=>['required',new ValiFullname()],
//            'password' => '|max:191|confirmed',
        ];
        if (isset($data['id'])) {
            $user = User::find($data['id']);
            $rules['email'] =['required',Rule::unique('users')->ignore($user->id),];
        }
        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }
        $data['file_image_id'] = @$data['file_image_new']['id'];
        if($data['file_image_id']){
            $data['image'] = str_replace('APP_URL', '',  $data['file_image_new']['uri']);

        }
        if (isset($data['id'])) {
            $entry = User::find($data['id']);

            if (!$entry) {
                return [
                    'code' => 3,
                    'message' => 'Không tìm thấy',
                ];
            }
//            if ($data['password']) {
//                $data['password'] = Hash::make($data['password']);
//            }

            $entry->fill($data);
            $entry->save();



            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
                'id' => $entry->id,
            ];
        }
    }
    public function save(Request $req)
    {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }
        $data = $req->get('entry');
        $data_role=$req->all();
        $roles = $req->roles;

        $rules = [
            'full_name'=>['required',new ValiFullname()],
//            'password' => '|max:191|confirmed',
        ];
        if (!isset($data['id'])) {
            $rules['username'] =  ['required','unique:users,username',new ValiUser()];
            $rules['email'] ='required|max:191|email|unique:users,email';

//            $rules['password'] = 'required|max:191|confirmed';

        }
        if (isset($data['id'])) {
            $user = User::find($data['id']);
            $rules['email'] =['required',Rule::unique('users')->ignore($user->id),];
//            $rules['password'] = 'required|max:191|confirmed';


        }
        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }
        if (isset($data['id'])) {
            $entry = User::find($data['id']);

            if (!$entry) {
                return [
                    'code' => 3,
                    'message' => 'Không tìm thấy',
                ];
            }
//            if ($data['password']) {
//                $data['password'] = Hash::make($data['password']);
//            }
            $entry->fill($data);
            $entry->save();



            UserRole::where('user_id', $entry->id)->delete();
            $data_role['user_id']=$entry->id;
            if($data_role['role']==1)
            {
                $data_role['user_id']=$entry->id;
                $data_role['role_id']=1;
            }
            if($data_role['role']==2)
            {
                $data_role['user_id']=$entry->id;
                $data_role['role_id']=2;
            }
            if($data_role['role']==4)
            {
                $data_role['user_id']=$entry->id;
                $data_role['role_id']=4;
            }
            if($data_role['role']==5)
            {
                $data_role['user_id']=$entry->id;
                $data_role['role_id']=5;
            }
            if($data_role['role']==12)
            {
                $data_role['user_id']=$entry->id;
                $data_role['role_id']=12;
            }
            UserRole::updateOrCreate([
                'user_id'=>$data_role['user_id'],
                'role_id'=>$data_role['role_id']

            ],$data_role);
            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
                'id' => $entry->id,
            ];
        } else {
            $entry = new User();
            $data['password'] = Hash::make($data['password']);
            $entry->fill($data);
            $entry->save();
            $data_role['user_id']=$entry->id;
            if($data_role['role']==1)
            {
                $data_role['user_id']=$entry->id;
                $data_role['role_id']=1;
            }
            if($data_role['role']==2)
            {
                $data_role['user_id']=$entry->id;
                $data_role['role_id']=2;
            }
            if($data_role['role']==4)
            {
                $data_role['user_id']=$entry->id;
                $data_role['role_id']=4;
            }
            if($data_role['role']==5)
            {
                $data_role['user_id']=$entry->id;
                $data_role['role_id']=5;
            }
            if($data_role['role']==12)
            {
                $data_role['user_id']=$entry->id;
                $data_role['role_id']=12;
            }
            if($data_role['role'])
            {
                UserRole::updateOrCreate([
                    'user_id'=>@$data_role['user_id'],
                    'role_id'=>@$data_role['role_id']
                ],$data_role);

            }

            return [
                'code' => 0,
                'message' => 'Đã thêm',
                'id' => $entry->id,
            ];
        }
    }

    /**
     * @param Request $req
     */
    public function toggleStatus(Request $req)
    {
        $id = $req->get('id');
        $entry = User::find($id);

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
     * @uri  /xadmin/users/data
     * @return  array
     */
    public function data(Request $req)
    {
        $query = User::query()
            ->with(['roles','fileImage'])
            ->orderBy('id', 'ASC');
        $last_updated = User::query()->orderBy('updated_at', 'desc')->first()->updated_at;
        $roles = Role::with(['users'])->orderBy('role_name', 'ASC')->get();
        if ($req->keyword) {
            $query->where('username', 'LIKE', '%' . $req->keyword . '%')
                ->orWhere('email', 'LIKE', '%' . $req->keyword . '%')
                ->orWhere('id', 'LIKE', '%' . $req->keyword . '%')
                ->orWhere('state','LIKE','%'.$req->keyword . '%')
                ->orwhereHas('roles', function ($q) use ($req) {
                    $q->where('role_name', 'LIKE', '%' . $req->keyword . '%');
                });
        }
        if ($req->role) {
            $query->whereHas('roles', function ($q) use ($req) {
                $q->where('role_name', 'LIKE', '%' . $req->role . '%');
            });
        }
        if ($req->full_name) {
            $query->where('full_name', 'LIKE', '%' . $req->full_name . '%');
        }
        if ($req->email) {
            $query->where('email', 'LIKE', '%' . $req->email . '%');
        }
        if ($req->state) {
            $query->where('state', 'LIKE', '%' . $req->state . '%');
        }
        $query->createdIn($req->created);
        $limit=25;
        if($req->limit)
        {
            $limit=$req->limit;
        }
        $entries = $query->paginate($limit);

        $users = $entries->items();
        $data = [];
        foreach ($users as $user) {
            $roles = $user->roles;
            $roleNames = [];
            if ($roles) {
                foreach ($roles as $role) {

                    $roleNames[] = $role->role_name;
                }
            }
            $data[] = [
                'role' => implode(',', $roleNames),
                'id' => $user->id,
                'username' => $user->username,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'state' => $user->state,
                'image'=>$user->image,
                'file_image_id'=>$user->file_image_id,
                'password' => $user->password,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];
        }
        return [
            'code' => 0,
            'data' => [
                'data' => $data,
                'last_updated' => $last_updated
            ],
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
                'totalRecord' => $entries->count(),
            ]
        ];
    }
    public function data_teacher(Request $req)
    {
        $query = User::query()
            ->with(['roles', 'user_devices'])
            ->orderBy('id', 'ASC');
        if ($req->keyword) {
            $query->where('username', 'LIKE', '%' . $req->keyword . '%');
        }


        $query->whereHas('roles', function ($q) use ($req) {
            $q->where('role_name', 'Teacher');
        });

        if ($req->username) {
            $query->where('username', 'LIKE', '%' . $req->username);
        }
        if ($req->full_name) {
            $query->where('full_name', 'LIKE' . $req->full_name);
        }
        if ($req->email) {
            $query->where('email', 'LIKE', '%' . $req->email);
        }
        if ($req->state) {
            $query->where('state', 'LIKE', '%' . $req->state);
        }
        $query->createdIn($req->created);
        $limit=25;
        if($req->limit)
        {
            $limit=$req->limit;
        }
        $entries = $query->paginate($limit);
        $users = $entries->items();
        return [
            'code' => 0,
            'data' => $users,
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
                'totalRecord' => $entries->count(),
            ]
        ];
    }
    public function export()
    {
        $keys = [
            'username' => ['A', 'username'],
            'password' => ['B', 'password'],
            'full_name' => ['C', 'full_name'],
            'email' => ['D', 'email'],
            'description' => ['E', 'description'],
            'sso_id' => ['F', 'sso_id'],
            'state' => ['G', 'state'],
            'remember_token' => ['H', 'remember_token'],
        ];
        $query = User::query()->orderBy('id', 'desc');
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
