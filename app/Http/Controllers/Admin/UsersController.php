<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TeacherErrorExport;
use App\Helpers\PermissionField;
use App\Imports\TeacherImport;
use App\Models\File;
use App\Models\RequestRole;
use App\Models\Role;
use App\Models\School;
use App\Models\UserCourseUnit;
use App\Models\UserDevice;
use App\Models\UserRole;
use App\Models\UserUnit;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use mysql_xdevapi\Exception;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
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
        $user=Auth::user();
        $permissionDetail = new PermissionField();
        $permissions = $permissionDetail->permission($user);
        $permissionFields = [
            'teacher_management_import' => $permissionDetail->havePermission('teacher_management_import',$permissions,$user),
            'teacher_management_create_new'=>$permissionDetail->havePermission('teacher_management_create_new',$permissions,$user),
        ];

        $jsonData = [
            'permissionFields'=>$permissionFields,
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
        $roles = Role::query()->orderBy('id', 'ASC')->where('role_name','<>','Super Administrator')->get();
        $jsonData = [
            'roles' => $roles,
            'schools' => $schools,
        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }

    public function create_teacher(Request $req)
    {
        $data=$req->all();
        $component = 'TeacherCreated';
        $title = 'Create Teacher';
        $roles = Role::query()->orderBy('role_name')->get();
        $user = Auth::user();
        $school=School::query()->where('id',$data['schoolId'])->first();
        $schoolName = $school->label;
        $jsonData = [
            'schoolName'=>$schoolName,
            'school' => $school,
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
        $entry = User::with(['roles', 'user_devices', 'schools'])
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
            if ($entry->file_image_new == NULL) {
                $entry->file_image_new = [
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


        @$devicePerUser = ($entry->schools->devices_per_user);


        @$userDevice = ($entry->user_devices);
        if ($devicePerUser != null && $userDevice != null) {
            @$userDe = round(($userDevice->count() / $devicePerUser) * 100);

        } else {
            $userDe = 0;
        }


        $user = Auth::user();
        if ($user->schools) {
            $license = ($user->schools->license_to);

        } else {
            $license = null;
        }
        $permissionDetail = new PermissionField();
        $permissions = $permissionDetail->permission($user);
        $permissionFields = [
            'profile_full_name' => $permissionDetail->havePermission('profile_full_name',$permissions,$user),
            'profile_email'=>$permissionDetail->havePermission('profile_email',$permissions,$user),
            'profile_role'=>$permissionDetail->havePermission('profile_role',$permissions,$user),
            'profile_set_password'=>$permissionDetail->havePermission('profile_set_password',$permissions,$user),
            'profile_change_avatar'=>$permissionDetail->havePermission('profile_change_avatar',$permissions,$user),
            'profile_user_name'=>$permissionDetail->havePermission('profile_user_name',$permissions,$user),
        ];


        /**
         * @var  User $entry
         */
        $title = 'Profile Edit';
        $component = 'ProfileForm';
        $jsonData = [
            'permissionFields'=>$permissionFields,
            'entry' => $entry,
            'role' => $role,
            'userDe' => $userDe,
            @'license' => @$license,
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
        $entry = User::query()->with(['roles', 'schools', 'user_devices'])
            ->where('id', $id)->first();
        if (!$entry) {
            throw new NotFoundHttpException();
        }

        /**
         * @var  User $entry
         */
        $roles = Role::query()->orderBy('order', 'ASC')->where('role_name', '<>', 'Super Administrator')->get();
        foreach ($entry->roles as $role) {
            @$title_role = $role->role_name;
        }

        if ($entry->roles) {
            foreach ($entry->roles as $role) {
                $name_role = $role->id;
            }
        }
        if ($entry->user_devices)
        {
            $userDevice=$entry->user_devices;
        }
        @$school = $entry->schools->label;
        $schools = School::query()->orderBy('label', 'ASC')->get();
        $title = 'Edit';
        $component = 'UserEdit';
        $user = Auth::user();
        $permissionDetail = new PermissionField();
        $permissions = $permissionDetail->permission($user);
        $permissionFields = [
            'user_username' => $permissionDetail->havePermission('user_username',$permissions,$user),
            'user_full_name'=>$permissionDetail->havePermission('user_full_name',$permissions,$user),
            'user_email'=>$permissionDetail->havePermission('user_email',$permissions,$user),
            'user_description'=>$permissionDetail->havePermission('user_description',$permissions,$user),
            'user_active'=>$permissionDetail->havePermission('user_active',$permissions,$user),
            'user_role'=>$permissionDetail->havePermission('user_role',$permissions,$user),
            'user_remove'=>$permissionDetail->havePermission('user_remove',$permissions,$user),
            'user_password'=>$permissionDetail->havePermission('user_password',$permissions,$user),
            'user_delete_device'=>$permissionDetail->havePermission('user_delete_device',$permissions,$user)

        ];
        if($entry->school_id)
        {
            $userSchool=explode(',',$entry->school_id);

        }
        $jsonData = [
            'userSchool'=>@$userSchool,
            'userDevice'=>@$userDevice,
            'permissionFields'=>$permissionFields,
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
        $schools = @$entry->schools;

        $allocationContentId=@$schools->allocation_school->allocation_content_id;

        $schools = @$entry->schools;
        $schoolId=@$entry->schools->id;
        $schoolCousers = ($schools->school_courses);
        $schoolUnits = $schools->school_course_units;
        $course_unit = $schools->units;
        $userUnits = $entry->user_units;


        if (@$schoolCousers) {
            $courseTeachers = [];
            foreach ($schoolCousers as $course) {
                $course['courseTea'] = [];
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
                $total_unit = [];

                foreach ($course_unit as $un) {
                    foreach ($schoolUnits as $unit) {
                        if ($un->id == $unit->unit_id && $course->id == $unit->course_id) {
                            $total_unit[] = $un;
                        }
                    }
                }

                $course['total_unit'] = $total_unit;

                $course['courseTea'] = $unitTeacher;
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
        $permissionDetail = new PermissionField();
        $permissions = $permissionDetail->permission($user);
        $permissionFields = [
            'teacher_username' => $permissionDetail->havePermission('teacher_username',$permissions,$user),
            'teacher_email'=>$permissionDetail->havePermission('teacher_email',$permissions,$user),
            'teacher_phone'=>$permissionDetail->havePermission('teacher_phone',$permissions,$user),
            'teacher_class'=>$permissionDetail->havePermission('teacher_class',$permissions,$user),
            'teacher_school'=>$permissionDetail->havePermission('teacher_school',$permissions,$user),
            'teacher_description'=>$permissionDetail->havePermission('teacher_description',$permissions,$user),
            'teacher_import'=>$permissionDetail->havePermission('teacher_import',$permissions,$user),

        ];
        $jsonData = [
            'permissionFields'=>$permissionFields,
            'allocationContentId'=>$allocationContentId,
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
    public function teacherDetails(Request $req)
    {
        $id = $req->id;
        $entry = User::query()->with('schools', 'user_devices', 'user_cousers', 'user_units', 'units', 'cousers')
            ->where('id', $id)->first();

        $userCousers = ($entry->user_cousers);
        $schools = @$entry->schools;
        $allocationContentId=@$schools->allocation_school->allocation_content_id;
        $schoolId=@$entry->schools->id;
        $schoolCousers = ($schools->school_courses);
        $schoolUnits = $schools->school_course_units;
        $course_unit = $schools->units;
        $userUnits = $entry->user_units;


        if (@$schoolCousers) {
            $courseTeachers = [];
            foreach ($schoolCousers as $course) {
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
                $total_unit = [];

                foreach ($course_unit as $un) {
                    foreach ($schoolUnits as $unit) {
                        if ($un->id == $unit->unit_id && $course->id == $unit->course_id) {
                            $total_unit[] = $un;
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
        $component = 'TeacherDetails';
        $user = Auth::user();
        $permissionDetail = new PermissionField();
        $permissions = $permissionDetail->permission($user);
        $permissionFields = [
            'teacher_username' => $permissionDetail->havePermission('teacher_username',$permissions,$user),
            'teacher_email'=>$permissionDetail->havePermission('teacher_email',$permissions,$user),
            'teacher_phone'=>$permissionDetail->havePermission('teacher_phone',$permissions,$user),
            'teacher_class'=>$permissionDetail->havePermission('teacher_class',$permissions,$user),
            'teacher_school'=>$permissionDetail->havePermission('teacher_school',$permissions,$user),
            'teacher_description'=>$permissionDetail->havePermission('teacher_description',$permissions,$user),
            'teacher_import'=>$permissionDetail->havePermission('teacher_import',$permissions,$user),

        ];
        $jsonData = [
            'permissionFields'=>$permissionFields,
            'allocationContentId'=>$allocationContentId,
            'entry' => $entry,
            @'user_device' => @$user_device,
            @'schools' => @$schools,
            @'courseTeachers' => @$courseTeachers,
            @'schoolCousers' => @$schoolCousers,
            //           @'course_unit' => @$course_unit,
            @'userCouser' => @$userCouser,
            @'userUnits' => @$userUnits,
            'schoolId'=>@$schoolId
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
        $auth=Auth::user();
        foreach ($auth->roles as $role)
        {
            $roleName=$role->role_name;
        }
        return [
            'code' => 0,
            'message' => 'Đã xóa',
            'object'=>$entry->username,
            'status'=>'Delete user',
            'role'=>$roleName
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
            'username' => ['required', 'min:6', 'unique:users,username', function ($attribute, $value, $fail) {
                if (preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $value)) {
                    return $fail(__(' The :attribute no special characters'));
                }
            },],
            'full_name' => ['required',function ($attribute, $value, $fail) {
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
            if ($data['email']) {
                $rules['email'] = ['email', Rule::unique('users')->ignore($user->id),];

            }
            $rules['username'] = ['required', Rule::unique('users')->ignore($user->id),];
        }
        $customMessages = [
            'full_name.required' => 'The full name field is required.',
        ];
        $v = Validator::make($data, $rules,$customMessages);

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
        $auth=Auth::user();
        foreach ($auth->roles as $role)
        {
            $roleName=$role->role_name;
        }
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
            $rules['username'] = ['required', 'min:6', 'unique:users,username', function ($attribute, $value, $fail) {
                if (preg_match('/[\'\/~`\!@#\$%\^&\*\(\)\-\+=\{\}\[\]\|;:"\<\>,\?\\\]/', $value)) {
                    return $fail(__(' The :attribute no special characters'));
                }
            },];
            // $rules['email'] = ['email','unique:users,email',];

//            $rules['password'] = 'required|max:191|confirmed';
        }
        if (isset($data['id'])) {
            $user = User::find($data['id']);
            if ($data['email']) {
                $rules['email'] = ['email', Rule::unique('users')->ignore($user->id),];

            }


        }
        if(!isset($data['id']))
        {

           if($data_role['name_role']==null)
           {
               $rules['name_role']=['required'];
           }

        }
//        if ($data_role['name_role'] == 2 || $data_role['name_role'] == 5) {
//            $rules['school_id'] = ['required'];
//        }
        $customMessages = [
//            'school_id.required' => 'The school field is required.',
            'name_role.required'=>'The role field is required.'
        ];
        $v = Validator::make($data, $rules, $customMessages,$data_role);
        $v->after(function($validate ) use ($data_role,$data)
        {
            if(isset($data['id']) && $data_role['password']!=$data_role['password_confirmation'])
            {
                $validate->errors()->add('password_confirmation','The password and confirmation password do not match.');

            }
            if(!isset($data['id']) && $data['password']!=$data_role['password_confirmation'] && $data_role['auto_gen']==false)
            {
                $validate->errors()->add('password_confirmation','The password and confirmation password do not match.');

            }

        });


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
            if(@$data_role['userSchool'])
            {
                $userSchools = implode(',', $data_role['userSchool']);
                User::where('id',$entry->id)->update(['school_id'=>$userSchools]);
            }
            $entry->fill($data);
           if($data_role['password'] !=null)
           {
               $entry->password = Hash::make($data_role['password']);
               User::where('id',$entry->id)->update(['password'=>$entry->password]);
           }

            $entry->save();
         $userUnits= UserUnit::query()->where('user_id',$entry->id)->get();
         $schoolUnitIds=[];
         $schoolCourseIds=[];
        foreach($userUnits as $userUnit)
        {
            if($userUnit->school_id!=$entry->school_id)
            {
                $schoolUnitIds[]=$userUnit->school_id;
            }
        }
        UserUnit::whereIn('school_id',$schoolUnitIds)->where('user_id',$entry->id)->delete();
        $userCousers=UserCourseUnit::query()->where('user_id',$entry->id)->get();
        foreach($userCousers as $userCouser)
        {
            if($userCouser->school_id!=$entry->school_id)
            {
                $schoolCourseIds[]=$userCouser->school_id;
            }
        }
        UserCourseUnit::whereIn('school_id',$schoolCourseIds)->where('user_id',$entry->id)->delete();
            $schoolId = @$entry->schools->id;

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

            return [
                'code' => 0,
                'role'=>$roleName,
                'object'=>$entry->username,
                'status'=>'Update User',
                'message' => 'Đã cập nhật',
                'id' => $entry->id,
            ];
        } else {
            $entry = new User();
            if (@$data['password'] == null) {
                $entry->password = Str::random(10);
                $realPassword = $entry->password;
                $entry->password = Hash::make($entry->password);

            }
            if (@$data['password'] != null) {
                $realPassword = $data['password'];
                $data['password'] = Hash::make($data['password']);
            }
            $entry->fill($data);
            $entry->save();
            if(@$data_role['userSchool'])
            {
                $userSchools = implode(',', $data_role['userSchool']);
                User::where('id',$entry->id)->update(['school_id'=>$userSchools]);
            }
            if ($entry->email) {
                $content = [
                    'full_name' => $entry->full_name,
                    'password' => $realPassword,
                    'username' => $entry->username,
                ];
                dispatch(new SendMailPassword($entry->email, 'New account information', $content));
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
                'status'=>'Create new user',
                'object'=>$entry->username,
                'role'=>$roleName,
                'message' => 'Đã thêm',
                'id' => $entry->id,
            ];
        }
    }

    public function saveTeacher(Request $req)
    {
        $data_role = $req->all();
        $data = $req->get('entry');
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }
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

            if(@$data['phone'])
            {
                $rules['phone']=['min:10'];
            }
        if (!isset($data['id'])) {
            if($data_role['auto_gen']==false)
            {
                $rules['password']=['required'];
            }
            if(@$data['password'])
            {
                $rules['password_confirmation']=['required'];
            }
            $rules['username'] = ['required', 'min:6', 'unique:users,username', function ($attribute, $value, $fail) {
                if (preg_match('/[\'\/~`\!@#\$%\^&\*\(\)\-\+=\{\}\[\]\|;:"\<\>,\?\\\]/', $value)) {
                    return $fail(__(' The :attribute no special characters'));
                }
            },];
            $rules['email'] = 'email|unique:users,email';

        }
        $user = Auth::user();
        foreach ($user->roles as $role)
        {
            $roleName=$role->role_name;
        }
        // if($roleName=='School Admin')
        // {
        //     $schoolId = $data_role['school']->id;

        // }
        // if($roleName!='School Admin')
        // {
        //     $schoolId=$data_role['schoolId'];
        // }
        if (isset($data['id'])) {
            $user = User::find($data['id']);
            if ($data['email']) {
                $rules['email'] = ['email', Rule::unique('users')->ignore($user->id),];
            }
            if (@$data_role['courseTeachers']==[]) {
                $rules['courseTeachers'] = ['required'];
            }

            if (@$data_role['courseTeachers']==[]) {
                $rules['courseTeachers'] = ['required'];
            }
            if(@$data_role['courseTeachers'])
            {
                foreach($data_role['courseTeachers'] as $courseTeacher)
                {
                    foreach ($data_role['unit'] as $unit)
                    {
                        if($unit['id']==$courseTeacher)
                        {
                            if(!$unit['courseTea'])
                            {
                                $rules['courseTea'] = ['required'];
                            }
                        }
                    }
                }
            }
        }


        $customMessages = [
            'school_id.required' => 'The school field is required.',
            'courseTeachers.required'=>'The course field is required.',
           'courseTea.required'=>'The unit field is required.'
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
            $entry->fill($data);
            $entry->save();
            $schoolId = $data['school_id'];
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
                    UserCourseUnit::create(['user_id' => $entry->id, 'course_id' => $courseTeacherId, 'school_id' => $schoolId,'allocation_content_id'=>$data_role['allocationContentId']]);

                }
            }
            UserUnit::where('user_id', $entry->id)->delete();
            if (@$data_role['unit']) {
                foreach ($data_role['unit'] as $UnitId) {
                    if (@$UnitId['courseTea']) {
                        foreach ($UnitId['courseTea'] as $uni) {
                            if (in_array($UnitId['id'], $data_role['courseTeachers'])) {
                                UserUnit::create(['user_id' => $entry->id, 'unit_id' => $uni, 'course_id' => $UnitId['id'], 'school_id' => $schoolId,'allocation_content_id'=>$data_role['allocationContentId']]);

                            }
                        }
                    }
                }
            }

            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
                'object'=>$entry->username,
                'status'=>'Update user',
                'role'=>$roleName,
                'id' => $entry->id,
            ];
        }
        else{
            $entry = new User();
            $entry->school_id =$data_role['school']['id'];
            if (@$data['password'] == null) {
                $entry->password = Str::random(10);
                $realPassword = $entry->password;
                $entry->password = Hash::make($entry->password);

            }
            if (@$data['password'] != null) {
                $realPassword = $data['password'];
                $data['password'] = Hash::make($data['password']);
            }
            $entry->fill($data);
            $entry->save();
            if ($entry->email) {
                $content = [
                    'full_name' => $entry->full_name,
                    'password' => $realPassword,
                    'username' => $entry->username,
                ];
                dispatch(new SendMailPassword($entry->email, 'New account information', $content));
            }
            UserRole::create(['user_id' => $entry->id, 'role_id' => 5]);

            return [
                'code' => 0,
                'message' => 'Đã thêm',
                'id' => $entry->id,
                'object'=>$entry->username,
                'status'=>'Create new user',
                'role'=>$roleName,
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
        $user = Auth::user();
        $school_id = $user->schools->id;
        $lengthUserSchool=$user->schools->number_of_users;
       $devicePerUser=$user->schools->devices_per_user;
        $query = User::query()
            ->with(['roles', 'user_devices'])
            ->where('school_id', '=', $school_id)
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
            'devicePerUser'=>$devicePerUser,
            'lengthUserSchool'=>$lengthUserSchool,
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
       $users= User::query()->orWhereHas('roles',function ($q)
        {
            $q->where('role_name','Super Administrator');

        })->get();
        $check=0;
        foreach ($users as $user)
        {
            foreach ($ids as $id)
            {

                if($id==$user->id)
                {
                    $check=1;

                }

            }
        }

        if($check==0)
        {
            UserRole::whereIn('user_id', $ids)->delete();
            User::whereIn('id', $ids)->delete();
            return [
                'code' => 0,
                'message' => 'Đã xóa'
            ];
        }
        else{
            return  [
              'code' => 1,
            ];
        }




    }

    public function validateImportTeacher(Request $req)
    {
        $data=$req->all();
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
        $sheets = Excel::toCollection(new TeacherImport(), "{$y}/{$m}/{$hash}.{$extension}", 'excel-import');
        $teacherLists[] = $sheets;
        $validations = [];
        $error = [];
        $user = Auth::user();
        $school=School::query()->where('id',$data['school_id'])->first();
        $code = 0;
        foreach ($teacherLists as $teacherList) {

            foreach ($teacherList as $teacher) {

                foreach ($teacher as $key => $tea) {
                    if($key>6 && $tea[0]!=null)
                    {
                        $item = [];
                        $item['username'] = $tea[0];
                        $item['full_name'] = $tea[1];
                        $item['password'] = $tea[2];
                        $item['phone'] = $tea[3];
                        $item['email'] = $tea[4];
                        $item['class'] = $tea[5];
                        $validator = Validator::make($item, [
                            'username' => ['required', Rule::unique('users', 'username')],
                            'full_name' => ['required',
                                function ($attribute, $value, $fail) {
                                    if (preg_match('/[0-9]/', $value)) {
                                        return $fail(__(' The :attribute not a number'));
                                    }
                                },
                            ],
                            'password' => 'required',
                            'email' => [Rule::unique('users', 'email')],
                        ]);

                        if ($validator->fails()) {
                            $item['error'] = $validator->errors()->messages();
                            $code = 2;//Có lỗi
                            /* return [
                                 'code' => 2,
                                 'errors' => $validator->errors()
                             ];*/
                        }
                        $validations[] = $item;


                    }
                }
            }
            $checkDuplicate=[];
            $error=[];
            foreach ($validations as $validation)
            {
                if(in_array($validation['username'], $checkDuplicate)){
                    $error[] = $validation['username'];
                }
                else{
                    $checkDuplicate[]=$validation['username'];
                }
            }
         if($error!=[])
         {
             $code=2;
         }
         if(count($validations)>$school->number_of_users)
         {
             $code=2;
         }

            $fileError = [];
            $fileImport=[];
            if ($code == 2) {
                //export
                foreach ($validations as $key=>$validation) {

                        $validation['error']=[
                            'max_length'=>[
                                'Allowed to register up to '. $school->number_of_users .' users'
                            ]
                        ];

                    if (@$validation['error'] || $error!=[] && $error==[$validation['username']]) {
                        {
                            if($error!=[] && $error==[$validation['username']])
                            {
                                $validation['error']=[
                                    'username'=>[
                                        'The username has already been taken.'],
                                ];
                            }
                            $fileError[] =$validation;

                        }
                    }
                    else{
                        $fileImport[]=$validation;
                    }
                }
                $errorName = 'export_teacher_error_'. uniqid(time());
                Cache::add($errorName, json_encode($fileError));
                return [
                  'code'=> 2,
                  'fileImport' =>$fileImport,
                  'fileError'=>$fileError,
                    'errorFileName'=>$errorName
                ];
            }
             else {
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
                    'fileImport'=>$validations
                ];
            }

        }

    }
    public function exportErrorTeacher(Request $req)
    {
        $fileName=$req->fileError;
        $fileError=json_decode(Cache::get($fileName),true);
        Cache::forget($fileName);
        return Excel::download(new TeacherErrorExport($fileError), "File_import_teacher_error.xlsx");
    }
    public function downloadTemplate() : BinaryFileResponse
    {
        return response()->download(public_path('sample/Import_Teacher_Template.xlsx'));
    }
    public function import(Request $req)
    {
        $dataImport = $req->all();

        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }
            if ($dataImport['fileImport']!=[]) {
                $user=Auth::user();
                foreach ($dataImport['fileImport'] as $import) {
                    $user = new User();
                    $user->username = $import['username'];
                    $user->full_name=$import['full_name'];
                    $user->phone=$import['phone'];
                    $user->email=$import['email'];
                    $user->class=$import['class'];
                    $user->password=Hash::make($import['password']);
                    $user->school_id=$dataImport['school_id'];
                    $user->state =1;
                    $user->save();
                    UserRole::create(['user_id' => $user->id, 'role_id' => 5]);
                }
            return [
                'code' => 0,
                'message' => 'Đã cập nhật '
            ];
        }
            else{
                return [
                    'message'=>'Không có teacher nào được thêm'
                ];

            }
    }
    public function deleteDevice (Request $req)
    {
        $id=$req->id;
        UserDevice::where('id',$id)->delete();
        return [
            'code'=> 0,
            'message'=> 'Đã xóa'
        ];
    }
    public function refuseDevice(Request $req)
    {
        $id=$req->id;
        UserDevice::where('id',$id)->update(['delete_request'=>Null]);
        return [
            'code'=>0,
            'message'=>'Đã cập nhật'
        ];
    }
}
