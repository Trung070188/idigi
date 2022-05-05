<?php

namespace App\Http\Controllers\Admin;


use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


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
    public function index(Request $request) {
        $title = 'Users';
        $component = 'UserIndex';
        $roles=Role::query()->orderBy('role_name')->get();
        $jsonData=[
          'roles'=>$roles
        ];


//        dd($entry);


        return view('admin.layouts.vue', compact('title','component','jsonData'));
    }

    /**
     * Create new entry
     * @uri  /xadmin/users/create
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function create (Request $req) {
        $component = 'UserForm';
        $title = 'Create users';
        $roles=Role::query()->orderBy('role_name')->get();
        $jsonData=[
            'roles'=>$roles
        ];

        return view('admin.layouts.vue', compact('title','component','jsonData'));
    }
    /**
     * Index page
     * @uri  /xadmin/users/index
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function profile(Request $req)
    {   $id=$req->id;
        $entry = User::find($id);
        if (!$entry) {
            throw new NotFoundHttpException();
        }

        /**
         * @var  User $entry
         */
        $title = 'Profile Edit';
        $component = 'ProfileForm';
        $jsonData=[
            'entry'=>$entry,
        ];
        return view('admin.layouts.vue', compact('title','component','jsonData'));
    }

    /**
     * @uri  /xadmin/users/edit?id=$id
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function edit (Request $req) {
        $id = $req->id;
        $entry = User::find($id);
//        $entry->roles()->acttch();
        if (!$entry) {
            throw new NotFoundHttpException();
        }

        /**
         * @var  User $entry
         */
        $title = 'Edit';
        $component = 'UserForm';
        $roles=Role::query()->orderBy('role_name')->get();
        $jsonData=[
            'entry'=>$entry,
            'roles'=>$roles
        ];
        return view('admin.layouts.vue', compact('title','component','jsonData'));
    }

    /**
     * @uri  /xadmin/users/remove
     * @return  array
     */
    public function remove(Request $req) {
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

    /**
     * @uri  /xadmin/users/save
     * @return  array
     */
    public function save(Request $req) {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }
        $data = $req->get('entry');
        $rules = [
            'username' => 'required|max:191',
            'email' => 'required|max:191',
        ];
        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }
//        $data['state'] = ($data['state'] == 'true' || $data['state'] ==1) ? 1 : 0;
        /**
         * @var  User $entry
         */

       if (isset($data['id'])) {
            $entry = User::find($data['id']);
           if (!$entry) {
               return [
                    'code' => 3,
                    'message' => 'Không tìm thấy',
                ];
            }
//           $roles = $data['roles'];
//
//           UserRole::where('user_id', $entry->id)->delete();
//
//           foreach ($roles as $role){
//               User::create([
//                   'user_id' => $entry->id,
//                   'role_id' => $role
//               ]);
//           }
           $entry->fill($data);
           $entry->save();
           return [
                'code' => 0,
               'message' => 'Đã cập nhật',
                'id' => $entry->id,
           ];
        } else {
//
           $id=$req->id;
//           $entry=User::find($id)->roles;
          $entry=new User();
          $entry->fill($data);
//           $roles = $data['roles'];
//           UserRole::where('user_id', $id)->delete();
//
//
//           foreach ($roles as $role){
//               User::create([
//                   'user_id' => $entry->id,
//                   'role_id' => $role
//               ]);
//           }
          $entry->save();
           return [
               'code' => 0,
              'message' => 'Đã thêm',
               'id' => $entry->id,
            ];
        }
    }

    /**
     * @param  Request $req
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
    public function data(Request $req) {
        $query = User::query()
            ->with(['roles'])
            ->orderBy('id', 'desc');
        if ($req->keyword) {
            $query->where('username', 'LIKE', '%' . $req->keyword. '%');
        }
        if ($req->username) {
            $query->where('username',  $req->username);

        }
        if ($req->email) {
            $query->where('email',  $req->email);
        }
        if ($req->full_name) {
            $query->where('full_name',  $req->full_name);
        }
        if ($req->user_id) {
            $query->where('user_id',  $req->user_id);
        }
        $query->createdIn($req->created);
        $entries = $query->paginate();

        $users  = $entries->items();
        $data = [];

        foreach ($users as $user){
            $roles = $user->roles;

            $roleNames = [];

            if($roles){
                foreach($roles as $role){
                    $roleNames[] = $role->role_name;
                }
            }
            $data[] = [
                'role' => implode(',', $roleNames),
                'id'=>$user->id,
                'username' => $user->username,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'state'=>$user->state,
                'created_at' => $user->created_at
            ];
        }
        return [
            'roles'=>$roles,
            'code' => 0,
            'data' =>$data,
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
                'totalRecord' => $entries->count(),
            ]
        ];
    }

    public function export() {
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
