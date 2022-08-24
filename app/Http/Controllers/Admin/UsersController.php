<?php

namespace App\Http\Controllers\Admin;

use App\Imports\TeacherImport;
use App\Models\File;
use App\Models\RequestRole;
use App\Models\Role;
use App\Models\School;
use App\Models\UserCourseUnit;
use App\Models\UserDevice;
use App\Models\UserRole;
use App\Models\UserUnit;
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
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Collection\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Validation\Rule;
use App\Jobs\SendMailPassword;



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

    public function teacher(Request $request)
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
        $schools = School::query()->orderBy('id')->get();
        $roles = Role::query()->orderBy('id', 'ASC')->get();
        $jsonData = [
            'roles' => $roles,
            'schools' => $schools,
        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }

    public function create_teacher(Request $req)
    {
        $component = 'TeacherCreated';
        $title = 'Create Teacher';
        $roles = Role::query()->orderBy('role_name')->get();
        $user=Auth::user();
        $school=($user->schools->label);
        $jsonData = [
            'school'=>$school,
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
        $entry = User::with(['roles','user_devices','schools'])
            ->where('id', $id)->first();
        if (!$entry) {
            throw new NotFoundHttpException();
        }

        if (@$entry->fileImage) {
            $entry->file_image_new = [
                'id' => @$entry->fileImage->id,
                'uri' => @$entry->fileImage->url,
                'is_image' => 1,
            ];
        } else {
            $entry->file_image_new = NULL;
            if( $entry->file_image_new==NULL)
            {
                $entry->file_image_new=[
                    'id' => Null,
                    'uri' => Null,
                    'is_image' => Null,

                ];
            }
        }


        $role = '';
        foreach ($entry->roles as $role_id) {
            $role = $role_id->role_name;

        }



                    @$devicePerUser=($entry->schools->devices_per_user);


            @$userDevice=($entry->user_devices);
            if($devicePerUser!=null && $userDevice!=null)
            {
                @$userDe=round(($userDevice->count()/$devicePerUser)*100);

            }
            else{
                    $userDe=0;
            }


            $user=Auth::user();
            if($user->schools)
            {
                $license=($user->schools->license_to);

            }
            else{
                $license=null;
            }


        /**
         * @var  User $entry
         */
        $title = 'Profile Edit';
        $component = 'ProfileForm';
        $jsonData = [
            'entry' => $entry,
            'role' => $role,
            'userDe'=>$userDe,
            @'license'=>@$license,
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
        $entry = User::query()->with(['roles', 'schools'])
            ->where('id', $id)->first();
        if (!$entry) {
            throw new NotFoundHttpException();
        }

        /**
         * @var  User $entry
         */
        $roles = Role::query()->orderBy('id', 'ASC')->get();
        foreach ($entry->roles as $role) {
            @$title_role = $role->role_name;
        }

        if ($entry->roles) {
            foreach ($entry->roles as $role) {
                $name_role = $role->id;
            }
        }
        @$school = $entry->schools->label;
        $schools = School::query()->orderBy('label', 'ASC')->get();
        $title = 'Edit';
        $component = 'UserEdit';
        $user = Auth::user();
        $jsonData = [
            'schools' => $schools,
            @'school' => $school,
            'entry' => $entry,
            'roles' => $roles,
            @'name_role' => @$name_role,
            @'title_role' => @$title_role

        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }

    public function nameSideBar(Request $req)
    {
        $users = User::with(['roles'])->orderBy('username')->get();
        $data = [];
        foreach ($users as $user) {
            $userss = Auth::user();
            if ($user->id == $userss->id) {
                foreach ($userss->roles as $roless) {
                    $role = $roless->role_name;
                }
                return [
                    'username' => $user->full_name,
                    'role' => $role,
                    'image' => $user->image,

                ];

            }
        }
//
    }

    public function editTeacher(Request $req)
    {
        $id = $req->id;
        $entry = User::query()->with('schools', 'user_devices', 'user_cousers', 'user_units', 'units', 'cousers')
            ->where('id', $id)->first();

        $userCousers = ($entry->user_cousers);
        $schools = $entry->schools;
        $schoolCousers= ($schools->school_courses);
        $schoolUnits=$schools->school_course_units;
        $course_unit=$schools->units;
        $userUnits = $entry->user_units;


            if (@$schoolCousers) {
                $courseTeachers=[];
                foreach ( $schoolCousers as $course) {
                    $course['total_unit'] = [];

                    foreach ($userCousers as $userCouser) {
                        if ($userCouser->course_id == $course->id) {
                            $courseTeachers[] = $course->id;
                        }
                    }
                    $unitTeacher = [];
                        foreach ($userUnits as $userUnit) {
                            if ($userUnit->course_id == $course->id) {
                                $unitTeacher[] = $userUnit->unit_id;
                            }
                        }
                    $total_unit=[];

                        foreach ($course_unit as $un)
                        {
                            foreach ($schoolUnits as $unit)
                            {
                                if($un->id==$unit->unit_id && $course->id==$unit->course_id)
                                {
                                    $total_unit[]=$un;
                                }
                            }
                    }

                    @$course['total_unit'] = $total_unit;

                    @$course['courseTea'] = $unitTeacher;
                }
            }

        if (!$entry) {
            throw new NotFoundHttpException();
        }
        $user_device = $entry->user_devices;
        $schools = ($entry->schools);
        /**
         * @var  User $entry
         */

        $title = 'Edit';
        $component = 'TeacherEdit';
        $user = Auth::user();
        $jsonData = [
            'entry' => $entry,
            @'user_device' => @$user_device,
            @'schools' => @$schools,
            @'schoolCousers' => @$schoolCousers,
            @'courseTeachers' => @$courseTeachers,
 //           @'course_unit' => @$course_unit,
            @'userCouser' => @$userCouser,
            @'userUnits' => @$userUnits,
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

    public function removeDevice(Request $req)
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
            'old_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
            }]
        ];
        if (isset($data['id'])) {
            $rules['password'] = 'required||different:old_password';
            $rules['confirm_password'] = 'same:password';
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


            {
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

    public function saveProfile(Request $req)
    {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }
        $data = $req->get('entry');
        $data_role = $req->all();
        $roles = $req->roles;
        $rules = [
            'username' => ['required', 'min:8', 'unique:users,username', function ($attribute, $value, $fail) {
                if (preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $value)) {
                    return $fail(__(' The :attribute no special characters'));
                }
            },],
            'full_name' => ['required', function ($attribute, $value, $fail) {
                if (preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $value)) {
                    return $fail(__(' The :attribute no special characters'));
                }
            },
                function ($attribute, $value, $fail) {
                    if (preg_match('/[0-9]/', $value)) {
                        return $fail(__(' The :attribute not a number'));
                    }
                },
            ],
//            'password' => '|max:191|confirmed',
        ];

        if (isset($data['id'])) {
            $user = User::find($data['id']);
            if($data['email'])
            {
                $rules['email'] = [ 'email', Rule::unique('users')->ignore($user->id),];

            }

            $rules['username'] = ['required', Rule::unique('users')->ignore($user->id),];

        }
        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }
        $data['file_image_id'] = @$data['file_image_new']['id'];
        if ($data['file_image_id']) {
            $data['image'] = str_replace('APP_URL', '', $data['file_image_new']['uri']);

        }
        if (isset($data['id'])) {
            $entry = User::find($data['id']);
            $request_roles = RequestRole::query()->orderBy('id')->get();
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

        $data_role = $req->all();
        $rules = [
            'full_name' => ['required', function ($attribute, $value, $fail) {
                if (preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $value)) {
                    return $fail(__(' The :attribute no special characters'));
                }
            },
                function ($attribute, $value, $fail) {
                    if (preg_match('/[0-9]/', $value)) {
                        return $fail(__(' The :attribute not a number'));
                    }
                },
            ],
        ];
        if (!isset($data['id'])) {
            $rules['username'] = ['required', 'min:8', 'unique:users,username', function ($attribute, $value, $fail) {
                if (preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $value)) {
                    return $fail(__(' The :attribute no special characters'));
                }
            },];
            // $rules['email'] = ['email','unique:users,email',];

//            $rules['password'] = 'required|max:191|confirmed';
        }
        if (isset($data['id'])) {
            $user = User::find($data['id']);
            if($data['email'])
            {
                $rules['email'] = ['email', Rule::unique('users')->ignore($user->id),];

            }


        }
        $customMessages = [
        ];
        $v = Validator::make($data, $rules, $customMessages);

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
            $schoolId=$entry->schools->id;

            UserRole::where('user_id', $entry->id)->delete();
            if (@$data_role['name_role']) {
                UserRole::updateOrCreate([
                    'user_id' => $entry->id,
                    'role_id' => $data_role['name_role']
                ],
                    [
                        'user_id' => $entry->id,
                        'role_id' => $data_role['name_role']
                    ]
                );
            }
            UserCourseUnit::where('user_id', $entry->id)->delete();
            if (@$data_role['courseTeachers']) {
                foreach ($data_role['courseTeachers'] as $courseTeacherId) {
                    UserCourseUnit::create(['user_id' => $entry->id, 'course_id' => $courseTeacherId,'school_id'=>$schoolId]);

                }
            }
            UserUnit::where('user_id', $entry->id)->delete();
            if (@$data_role['unit']) {
                foreach ($data_role['unit'] as $UnitId) {
                    if (@$UnitId['courseTea']) {
                        foreach ($UnitId['courseTea'] as $uni) {
                            if(in_array($UnitId['id'], $data_role['courseTeachers'])){
                                UserUnit::create(['user_id' => $entry->id, 'unit_id' => $uni, 'course_id' => $UnitId['id'],'school_id'=>$schoolId]);

                            }
                        }
                    }
                }
            }

            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
                'id' => $entry->id,
            ];
        } else {
            $entry = new User();
            if(@$data['password']==null)
            {
               $entry->password=Str::random(10);
                $realPassword = $entry->password;
               $entry->password=Hash::make($entry->password);

            }
            if(@$data['password']!=null)
            {
                $data['password'] = Hash::make($data['password']);
                $realPassword = $data['password'];
            }
            $entry->fill($data);
            $entry->save();
          if($entry->email)
          {
              $content=[
                  'full_name'=>$entry->full_name,
                  'password'=>$realPassword,
                  'username'=>$entry->username,
              ];
              dispatch(new SendMailPassword($entry->email,'Thông báo tài khoản mới trên iDIGI',$content));
          }
            if ($data_role['name_role']) {
                UserRole::updateOrCreate([
                    'user_id' => @$entry->id,
                    'role_id' => @$data_role['name_role']
                ], [
                    'user_id' => @$entry->id,
                    'role_id' => @$data_role['name_role']
                ]);

            }

            return [
                'code' => 0,
                'message' => 'Đã thêm',
                'id' => $entry->id,
            ];
        }
    }
    public function saveTeacher(Request $req)
    {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }
        $data = $req->get('entry');
        $rules = [
            'full_name' => ['required', function ($attribute, $value, $fail) {
                if (preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $value)) {
                    return $fail(__(' The :attribute no special characters'));
                }
            },
                function ($attribute, $value, $fail) {
                    if (preg_match('/[0-9]/', $value)) {
                        return $fail(__(' The :attribute not a number'));
                    }
                },
            ],
//            'password' => '|max:191|confirmed',
        ];
        if (!isset($data['id'])) {
            $rules['username'] = ['required', 'min:8', 'unique:users,username', function ($attribute, $value, $fail) {
                if (preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $value)) {
                    return $fail(__(' The :attribute no special characters'));
                }
            },];
            $rules['email'] = 'email|unique:users,email';


//            $rules['password'] = 'required|max:191|confirmed';
        }
        // if (isset($data['id'])) {
        //     $user = User::find($data['id']);
        //     // $rules['email'] = ['required', 'email', Rule::unique('users')->ignore($user->id),];
        // }
        $customMessages = [
        ];
        $v = Validator::make($data, $rules, $customMessages);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }
        $user=Auth::user();
        $schoolId=$user->Schools->id;

            $entry = new User();
            $entry->school_id=$schoolId;
            if(@$data['password']==null)
            {
               $entry->password=Str::random(10);
                $realPassword = $entry->password;
               $entry->password=Hash::make($entry->password);

            }
            if(@$data['password']!=null)
            {
                $realPassword = $data['password'];
                $data['password'] = Hash::make($data['password']);
            }            $entry->fill($data);
            $entry->save();
            if($entry->email)
            {
                $content=[
                    'full_name'=>$entry->full_name,
                    'password'=>$realPassword,
                    'username'=>$entry->username,
                ];
                dispatch(new SendMailPassword($entry->email,'Thông báo tài khoản mới trên iDIGI',$content));
            }
        UserRole::create(['user_id'=>$entry->id,'role_id'=>5]);

            return [
                'code' => 0,
                'message' => 'Đã thêm',
                'id' => $entry->id,
            ];
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
        $entry->state = $req->state ? 0 : 1;
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
            ->with(['roles', 'fileImage'])
            ->orderBy('id', 'ASC');
        $last_updated = User::query()->orderBy('updated_at', 'desc')->first()->updated_at;
        $roles = Role::with(['users'])->orderBy('role_name', 'ASC')->get();
        if ($req->keyword) {
            $query->where('username', 'LIKE', '%' . $req->keyword . '%')
                ->orWhere('email', 'LIKE', '%' . $req->keyword . '%')
                ->orWhere('id', 'LIKE', '%' . $req->keyword . '%')
                ->orWhere('state', 'LIKE', '%' . $req->keyword . '%')
                ->orwhereHas('roles', function ($q) use ($req) {
                    $q->where('role_name', 'LIKE', '%' . $req->keyword);
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

        if ($req->state != '') {
            $query->where('state', $req->state);
        }
        $query->createdIn($req->created);
        $limit = 25;
        if ($req->limit) {
            $limit = $req->limit;
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
                'image' => $user->image,
                'file_image_id' => $user->file_image_id,
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

    public function dataTeacher(Request $req)
    {
        $user=Auth::user();
        $school_id=$user->schools->id;
        $query = User::query()
            ->with(['roles', 'user_devices'])
            ->where('school_id','=',$school_id)
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
        if ($req->state != '') {
            $query->where('state', 'LIKE', '%' . $req->state);
        }
        $query->createdIn($req->created);
        $limit = 25;
        if ($req->limit) {
            $limit = $req->limit;
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

    public function removeAll(Request $req)
    {
        $ids = $req->ids;
        UserRole::whereIn('user_id', $ids)->delete();
        User::whereIn('id', $ids)->delete();
        return [
            'code' => 0,
            'message' => 'Đã xóa'
        ];
    }
    public function saveImportTeacher(Request $req)
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
                'message' => 'Extension: '.$extension.' is now allowed'
            ];
        }

        $dir = public_path("uploads/excel_import/{$y}/{$m}");
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $info = pathinfo($file0['name']);
        $extension = strtolower($info['extension']);

        $hash = sha1(uniqid());
        $newFilePath = $dir.'/'.$hash.'.'.$extension;
        $ok = move_uploaded_file($file0['tmp_name'], $newFilePath);
        $newUrl = url("/uploads/excel_import/{$y}/{$m}/{$hash}.{$extension}");
        $sheets = Excel::toCollection(new TeacherImport(), "{$y}/{$m}/{$hash}.{$extension}", 'excel-import');
     $teacherLists[]= $sheets;

        $user=Auth::user();
        $school=$user->schools->id;
   foreach ($teacherLists as $teacherList)
   {

       foreach($teacherList as $teacher)
       {
           foreach ($teacher as $key=> $tea)
           {
               $tea=
                   [
                       '*.0'=>'required',
                       '*.1'=>'required',
                   ];
               $v = Validator::make($tea);
               if ($v->fails()) {
                   return [
                       'code' => 2,
                       'errors' => $v->errors()
                   ];
               }

               {
                   if($key>0)
                   {
                       $entry = new User();
                       $entry->username=$tea[0];
                       $entry->full_name=$tea[1];
                       $entry->school_id=$school;
                       $entry->password=Hash::make($tea[2]);
                       $entry->phone=$tea[3];
                       $entry->email=$tea[4];
                       $entry->save();
                       UserRole::create(['user_id'=>$entry->id,'role_id'=>5]);
                   }

               }
           }
       }
   }

        //luu bang file
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
            'code' => 0,
            'message' => 'Đã thêm',
            'id' => $entry->id
        ];
    }

}
