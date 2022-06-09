<?php

namespace App\Http\Controllers\Admin;


use App\Models\Role;
use App\Models\School;
use App\Models\UserDevice;
use App\Models\UserRole;
use App\Rules\ValiFullname;
use App\Rules\ValiUser;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Ramsey\Collection\Collection;
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
    public function index(Request $request)
    {
        $title = 'Users';
        $component = 'UserIndex';
        $roles = Role::query()->orderBy('role_name')->get();

        $jsonData = [
            'roles' => $roles
        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }
    public function index_teacher(Request $request)
    {
        $title = 'Teacher';
        $component = 'TeacherIndex';
        $roles = Role::query()->orderBy('role_name')->get();
        $jsonData = [
            'roles' => $roles
        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
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
        $jsonData = [
            'roles' => $roles
        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }

    public function create_teacher(Request $req)
    {
        $component = 'TeacherCreated';
        $title = 'Create Teacher';
        $roles = Role::query()->orderBy('role_name')->get();
        $jsonData = [
            'roles' => $roles
        ];

        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
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
        $entry = User::find($id);
        if (!$entry) {
            throw new NotFoundHttpException();
        }
        /**
         * @var  User $entry
         */
        $title = 'Profile Edit';
        $component = 'ProfileForm';
        $jsonData = [
            'entry' => $entry,
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
        $entry = User::query()
            ->where('id', $id)->first();
        if (!$entry) {
            throw new NotFoundHttpException();
        }

        /**
         * @var  User $entry
         */
        $roles = Role::query()->orderBy('role_name')->get();
        foreach ($roles as $role) {
            $role->user = false;
            if ($entry->roles) {

                foreach ($entry->roles as $userRole) {
                    if ($role->id == $userRole->id) {
                        $role->user = true;
                    }
                }
            }
        }
        $title = 'Edit';
        $component = 'UserEdit';
        $jsonData = [
            'entry' => $entry,
            'roles' => $roles,
        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
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
        $jsonData = [
            'entry' => $entry,
            'user_device' => $user_device
        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
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
    public function save(Request $req)
    {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }
        $data = $req->get('entry');
        $roles = $req->roles;
        $rules = [
            'username' => ['required',new ValiUser()],
            'full_name'=>['required',new ValiFullname()],
            'email' => 'required|max:191|email',
            'password' => '|max:191|confirmed',
        ];
        if (!isset($data['id'])) {
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
            foreach ($roles as $role) {
                if (@$role['user']) {
                    UserRole::create(['user_id' => $entry->id, 'role_id' => $role['id']]);
                }
            }
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
            foreach ($roles as $role) {
                if (@$role['user']) {
                    UserRole::create(['user_id' => $entry->id, 'role_id' => $role['id']]);
                }
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
            ->with(['roles'])
            ->orderBy('id', 'ASC');
        $last_updated = User::query()->orderBy('updated_at', 'desc')->first()->updated_at;
        $roles = Role::with(['users'])->orderBy('role_name', 'ASC')->get();
        if ($req->keyword) {
            $query->where('username', 'LIKE', '%' . $req->keyword . '%')
                ->orWhere('email', 'LIKE', '%' . $req->keyword . '%')
                ->orWhere('id', 'LIKE', '%' . $req->keyword . '%')
                ->orwhereHas('roles', function ($q) use ($req) {
                    $q->where('role_name', 'LIKE', '%' . $req->keyword . '%');
                });
        }
        if ($req->role) {
            $query->whereHas('roles', function ($q) use ($req) {
                $q->where('role_name', 'LIKE', '%' . $req->role);
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
        $entries = $query->paginate();
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
        if ($req->role) {
            $query->whereHas('roles', function ($q) use ($req) {
                $q->where('role_name', 'LIKE', '%' . $req->role);
            });
        }
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
        $entries = $query->paginate();
        $users = $entries->items();
        $data = [];
        foreach ($users as $user) {
            $roles = $user->roles;
            $user_devices = $user->user_devices;
            $data[] = [
                'id' => $user->id,
                'username' => $user->username,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'state' => $user->state,
                'password' => $user->password,
                'created_at' => $user->created_at,
                'roles' => $roles,
                'user_devices' => $user_devices,
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
