<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TeacherErrorExport;
use App\Helpers\PermissionField;
use App\Imports\SchoolImport;
use App\Imports\TeacherImport;
use App\Jobs\SendMailPassword;
use App\Models\AllocationContent;
use App\Models\AllocationContentSchool;
use App\Models\Course;
use App\Models\District;
use App\Models\File;
use App\Models\Province;
use App\Models\SchoolCourse;
use App\Models\SchoolCourseUnit;
use App\Models\User;
use App\Models\UserCourseUnit;
use App\Models\UserRole;
use App\Models\UserUnit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\School;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class SchoolsController extends AdminBaseController
{
    public static $menus = [
        [
            'name' => 'School',
            'icon' => 'fa fa-shopping-cart',
            'url' => '/xadmin/schools/index',
        ]
    ];

    /**
     * Index page
     * @uri  /xadmin/schools/index
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function index()
    {
        $title = 'School';
        $component = 'SchoolIndex';
        return component($component, compact('title'));
    }

    public function license()
    {
        $title = 'School';
        $component = 'LicenseIndex';
        return component($component, compact('title'));
    }

    /**
     * Create new entry
     * @uri  /xadmin/schools/create
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function create(Request $req)
    {
        $component = 'SchoolForm';
        $title = 'Create schools';
        $allocationContens = AllocationContent::query()->with(['course_unit', 'courses', 'units'])->orderBy('id', 'desc')->get()->toArray();
        //cap nhat content tu dong
        $newAllocationContents = [];
        foreach ($allocationContens as $allocationContent) {
            $units = $allocationContent['units'];
            // khoi tao array Course
            $arrayCourse = [];

            if (@$allocationContent['courses']) {

                foreach ($allocationContent['courses'] as $course) {
                    $newCourse = $course;

                    $newCourse['total_unit'] = [];
                    if (@$allocationContent['course_unit']) {
                        foreach (@$allocationContent['course_unit'] as $courseUnit) {
                            if ($course['id'] == $courseUnit['course_id']) {
                                $newCourse['total_unit'][] = $courseUnit['unit_id'];
                            }
                        }
                    }

                    $arrayCourse[] = $newCourse;
                }
            }
            $allocationContent['courses'] = $arrayCourse;

            $newAllocationContents[] = @$allocationContent;
        }
        $jsonData = [
            'newAllocationContents' => $newAllocationContents,
            'arrayCourse' => $arrayCourse,
            'units' => $units,


        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }

    public function createLicense(Request $req)
    {
        $component = 'LicenseForm';
        $title = 'Create schools';
        $school = School::query()->where('license_to')->orderBy('id', 'ASC')->get();
        $jsonData = [
            'school' => $school,
        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }

    public function editLicense(Request $req)
    {
        $id = $req->id;
        $entry = School::find($id);

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        /**
         * @var  Lesson $entry
         */
        $school = School::query()->orderBy('id', 'ASC')->get();
        $jsonData = [
            'school' => $school,
            'entry' => $entry,
        ];


        $title = 'Edit';
        $component = 'LicenseEdit';

        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }

    public function schoolNameNavBar(Request $req)
    {
        $user = Auth::user();
        if ($user->school_id) {
            $schoolIds = explode(',', $user->school_id);
            $schools = School::query()->where('id', $schoolIds)->get();
        }
        @$schoolName = $user->schools->label;
        foreach ($user->roles as $role) {
            @$roleName = $role->role_name;
        }

        return [
            'code' => 0,
            'schools' => $schools,
            'schoolName' => $schoolName,
            'roleName' => $roleName
        ];


    }

    public function dataTeacher(Request $req)
    {
        $id = $req->id;
        $countTeacher = User::query()->where('school_id', $id)->whereHas('roles', function ($q) {
            $q->where('role_name', 'Teacher');
        })->count();
        $entry = School::find($id);
        $query = User::query()
            ->with(['roles', 'user_devices'])
            ->where('school_id', '=', $entry->id)
            ->orderBy('id', 'ASC');
        if ($req->keyword) {
            $query->where('username', 'LIKE', '%' . $req->keyword . '%');
            $query->orWhere('full_name', 'LIKE', '%' . $req->keyword . '%');
        }
        $query->whereHas('roles', function ($q) use ($req) {
            $q->where('role_name', 'Teacher');

        });
        if ($req->full_name) {
            $query->where('full_name', 'LIKE', '%' . $req->full_name . '%');
        }
        if ($req->email) {
            $query->where('email', 'LIKE', '%' . $req->email . '%');
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
            'countTeacher' => $countTeacher,
            'code' => 0,
            'lengthDeviceTeacher' => $entry->devices_per_user,
            'data' => $users,
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
                'totalRecord' => $entries->count(),
            ]
        ];
    }

    public function teacherList(Request $req)
    {
        $title = 'TeacherList';
        $component = 'TeacherList';
        $id = $req->id;
        $entry = School::find($id);
        $userTotal = User::where('school_id', $id)->count();
        $isCreateTeacher = 1;

        if ($userTotal >= $entry->number_of_users) {
            $isCreateTeacher = 0;
        }

        $jsonData = [
            'entry' => $entry,
            'isCreateTeacher' => $isCreateTeacher,
        ];
        if (!$entry) {
            throw new NotFoundHttpException();
        }
        /**
         * @var  School $entry
         */

        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }

    /**
     * @uri  /xadmin/schools/edit?id=$id
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function edit(Request $req)
    {
        $id = $req->id;
        $entry = School::with(['allocation_contents', 'school_courses', 'school_course_units', 'allocation_school', 'users'])->where('id', $id)->first();
        $allocationContents = AllocationContent::query()->with(['course_unit', 'courses', 'units'])->orderBy('id', 'desc')->get()->toArray();
        $lengthTeacher = 0;
        @$active_allocation = $entry->active_allocation;
        if (@$entry->users) {
            $teacher = [];
            foreach ($entry->users as $user) {
                if (@$user->roles) {
                    foreach ($user->roles as $role) {
                        if ($role->role_name == 'Teacher') {
                            $teacher[] = $user;

                        }
                    }
                }
            }

            $lengthTeacher = count($teacher);
        }


        //load content tu dong
        $newAllocationContents = [];
        foreach ($allocationContents as $allocationContent) {

            $arrayCourse = [];

            if (@$allocationContent['courses']) {

                foreach ($allocationContent['courses'] as $course) {
                    $newCourse = $course;

                    $newCourse['total_unit'] = [];
                    if (@$allocationContent['course_unit']) {
                        foreach (@$allocationContent['course_unit'] as $courseUnit) {
                            if ($course['id'] == $courseUnit['course_id']) {
                                $newCourse['total_unit'][] = $courseUnit['unit_id'];
                            }
                        }
                    }

                    $arrayCourse[] = $newCourse;
                }
            }
            $allocationContent['courses'] = $arrayCourse;

            $newAllocationContents[] = @$allocationContent;
        }
        $allocationContentSchools = @$entry->allocation_contents;
        $allocationContentId = @$entry->allocation_school->allocation_content_id;
        $courses = null;
        if (@$entry->allocation_contents) {
            foreach ($allocationContentSchools as $allocationContentSchool) {

                $courses = ($allocationContentSchool->courses);
                $course_unit = $allocationContentSchool->course_unit;
                if ($allocationContentSchool->courses) {
                    foreach ($courses as $course) {
                        $course['unit'] = $course->unit;

                        $course['total_unit'] = [];
                        $total_unit = [];
                        foreach ($course_unit as $un) {

                            if ($un->course_id == $course->id) {
                                $total_unit[] = $un->unit_id;
                            }
                        }
                        @$course['total_unit'] = $total_unit;

                    }
                }


                $units = ($allocationContentSchool->units);
            }
        }

        foreach ($allocationContentSchools as $allocationContentSchool) {
            @$allocationContentSchoolName = $allocationContentSchool->title;
        }

        if (@$entry->school_courses) {
            $courses2 = $entry->school_courses;
            $course_unit2 = $entry->school_course_units;
            foreach ($courses2 as $course2) {
                $course2['unit'] = $course2->unit;

                $course2['total_unit'] = [];
                $total_unit2 = [];
                foreach ($course_unit2 as $un) {

                    if ($un->course_id == $course2->id) {
                        $total_unit2[] = $un->unit_id;
                    }
                }
                @$course2['total_unit'] = $total_unit2;

            }


        }


        if (!$entry) {
            throw new NotFoundHttpException();
        }

        /**
         * @var  School $entry
         */

        $user = Auth::user();
        foreach ($user->roles as $role) {
            $roleName = $role->role_name;
        }
        $permissionDetail = new PermissionField();
        $permissions = $permissionDetail->permission($user);
        $permissionFields = [
            'school_name' => $permissionDetail->havePermission('school_name', $permissions, $user),
            'school_address' => $permissionDetail->havePermission('school_address', $permissions, $user),
            'school_email' => $permissionDetail->havePermission('school_email', $permissions, $user),
            'school_phone_number' => $permissionDetail->havePermission('school_phone_number', $permissions, $user),
            'school_device' => $permissionDetail->havePermission('school_device', $permissions, $user),
            'school_user' => $permissionDetail->havePermission('school_user', $permissions, $user),
            'school_expire_date' => $permissionDetail->havePermission('school_expire_date', $permissions, $user),
            'school_description' => $permissionDetail->havePermission('school_description', $permissions, $user),
            'school_content' => $permissionDetail->havePermission('school_content', $permissions, $user),
            'school_delete' => $permissionDetail->havePermission('school_delete', $permissions, $user),
            'school_teacher_list' => $permissionDetail->havePermission('school_teacher_list', $permissions, $user),

        ];
        $title = 'Edit';
        $component = 'SchoolEdit';
        $entry->allocationContentId = $allocationContentId;
        $jsonData = [
            'active_allocation' => $active_allocation,
            'roleName' => @$roleName,
            'permissionFields' => $permissionFields,
            'teacher' => $lengthTeacher,
            'entry' => $entry,
            @'allocationContents' => @$newAllocationContents,
            @'allocationContentSchoolName' => @ $allocationContentSchoolName,
            'allocationContentId' => $allocationContentId,
            'courses' => $courses,
            @'units' => @$units,
            @'courses2' => @$courses2
        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }

    /**
     * @uri  /xadmin/schools/remove
     * @return  array
     */
    public function remove(Request $req)
    {
        $user = Auth::user();
        foreach ($user->roles as $role) {
            $roleName = $role->role_name;
        }
        $id = $req->id;
        $entry = School::find($id);

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        $entry->delete();

        return [
            'code' => 0,
            'message' => 'Đã xóa',
            'object' => $entry->label,
            'role' => $roleName,
            'status' => 'Delete school'
        ];
    }

    public function removeLicense(Request $req)
    {
        $id = $req->id;
        $entry = School::find($id);
        School::where('license_to', $entry->license_to)->delete();

        if (!$entry) {
            throw new NotFoundHttpException();
        }
        School::updateOrCreate(
            [
                'id' => $entry->id,
            ],
            [

                'label' => $entry->label,
                'school_email' => $entry->school_email,
                'school_phone' => $entry->school_phone,
                'school_description' => $entry->school_description,
                'number_of_users' => $entry->number_of_users,
                'devices_per_user' => $entry->devices_per_user,
                'license_state' => 0

            ]
        );
        return [
            'code' => 0,
            'message' => 'Đã xóa'
        ];
    }

    /**
     * @uri  /xadmin/schools/save
     * @return  array
     */
    public function save(Request $req)
    {
        $user = Auth::user();
        foreach ($user->roles as $role) {
            $roleName = $role->role_name;
        }

        $dataContent = $req->all();
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');
        $current = Carbon::now()->format('Y/m/d');
        $today = Carbon::now()->format('d/m/Y');


        $rules = [
            'label' => ['required', 'regex:/^[\p{L}\s\/0-9.,_-]+$/u'],
            'school_address' => ['required', 'max:255', 'regex:/^[\p{L}\s\/0-9.,_-]+$/u'],
            'province_id' => ['required'],
            'district_id' => ['required'],
            'number_of_users' => 'required|min:1|integer',
            'devices_per_user' => 'required|min:1|integer',
            'license_to' => 'required|after_or_equal:' . $current,
        ];
        if (@$data['school_email']) {
            $rules['school_email'] = ['email'];
        }
        if (@$data['school_phone']) {
            $rules['school_phone'] = ['regex:/^[0-9]{10}$/'];
        }
        // $rules['license_to']='after:' .$today;
        $message = [
            'label.required' => 'The school name field is required.',
            'number_of_users.required' => 'The No. of User field is required.',
            'devices_per_user.required' => 'The No. of Device per user field is required.',
            'license_to.required' => 'The Expired date/License is required.',
            'license_to.after_or_equal' => 'The Expired date/License must be a date after or equal to ' . $today,

        ];

        $v = Validator::make($data, $rules, $message, $dataContent);
        $v->after(function ($validate) use ($dataContent) {
            if (!isset($data['id']) && $dataContent['allocationContentSchool'] == "") {
                $validate->errors()->add('allocationContentSchool', 'Resource allocation field is required ');

            }
        });

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }

        /**
         * @var  School $entry
         */
        if (isset($data['id'])) {
            $entry = School::find($data['id']);
            if (!$entry) {
                return [
                    'code' => 3,
                    'message' => 'Không tìm thấy',
                ];
            }
            $this->activeAllocation($data);
            $entry->fill($data);
            $entry->save();
            AllocationContentSchool::where('school_id', $entry->id)->delete();
            SchoolCourseUnit::where('school_id', $entry->id)->delete();
            $userUnits = UserUnit::query()->where('school_id', '=', $entry->id)->get();
            foreach ($userUnits as $userUnit) {
                if ($userUnit->allocation_content_id != $dataContent['allocationContentSchool']) {
                    UserUnit::where('school_id', $entry->id)->delete();
                    UserCourseUnit::where('school_id', $entry->id)->delete();
                }
            }
            if (@$dataContent['allocationContentSchool']) {
                AllocationContentSchool::updateOrCreate(
                    [
                        'school_id' => $entry->id,
                    ],
                    [
                        'allocation_content_id' => $dataContent['allocationContentSchool']
                    ]
                );
//                AllocationContentSchool::create(['allocation_content_id' => $dataContent['allocationContentSchool'], 'school_id' => $entry->id]);

            }
            SchoolCourseUnit::where('school_id', $entry->id)->delete();
            foreach ($entry->allocation_contents as $contents) {
                foreach ($contents->course_unit as $schoolCourse) {
                    SchoolCourseUnit::create(['school_id' => $entry->id, 'course_id' => $schoolCourse->course_id, 'unit_id' => $schoolCourse->unit_id, 'allocation_content_id' => $dataContent['allocationContentSchool']]);
                }
            }
            SchoolCourse::where('school_id', $entry->id)->delete();
            foreach ($entry->allocation_contents as $contents) {
                foreach ($contents->courses as $schoolCourse) {
                    SchoolCourse::create(['school_id' => $entry->id, 'course_id' => $schoolCourse->id, 'allocation_content_id' => $dataContent['allocationContentSchool']]);
                }
            }

            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
                'id' => $entry->id,
                'role' => $roleName,
                'object' => $entry->label,
                'status' => 'Update school',
            ];
        } else {
            $entry = new School();
            $entry->fill($data);
            $entry->save();
            if (@$dataContent['allocationContentSchool']) {
                AllocationContentSchool::create(['allocation_content_id' => $dataContent['allocationContentSchool'], 'school_id' => $entry->id]);

                foreach ($entry->allocation_contents as $contents) {
                    foreach ($contents->course_unit as $schoolCourse) {
                        SchoolCourseUnit::create(['school_id' => $entry->id, 'course_id' => $schoolCourse->course_id, 'unit_id' => $schoolCourse->unit_id, 'allocation_content_id' => $dataContent['allocationContentSchool']]);
                    }
                }

                foreach ($entry->allocation_contents as $contents) {
                    foreach ($contents->courses as $schoolCourse) {
                        SchoolCourse::create(['school_id' => $entry->id, 'course_id' => $schoolCourse->id, 'allocation_content_id' => $dataContent['allocationContentSchool']]);
                    }
                }
            }
            return [
                'code' => 0,
                'message' => 'Đã thêm',
                'id' => $entry->id,
                'role' => $roleName,
                'object' => $entry->label,
                'status' => 'Create new school'
            ];
        }
    }

    public function activeAllocation($data)
    {
        $auth = Auth::user();
        $id = $data['id'];
        $schoolIds = User::query()->where('school_id', $id)->get();
        $userSchool = [];
        foreach ($schoolIds as $schoolId) {
            $userSchool[] = $schoolId->id;
        }
        if ($data['active_allocation'] == true) {
            School::where('id', $id)->update(['active_allocation' => 1, 'full_name_active_content' => $auth->full_name]);
            User::query()->WhereIn('id', $userSchool)->update(['active_allocation' => 1, 'full_name_active_content' => $auth->full_name]);

        } else {
            School::where('id', $id)->update(['active_allocation' => 0, 'full_name_active_content' => $auth->full_name]);
            User::query()->WhereIn('id', $userSchool)->update(['active_allocation' => 0, 'full_name_active_content' => $auth->full_name]);

        }
        return [
            'code' => 0,
            'message' => 'Đã cập nhật'
        ];
    }

    public function saveEditLicense(Request $req)
    {
        $dataContent = $req->all();
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');

        $rules = [
//            'label' => 'required|max:45',
            'license_to' => 'required',
        ];

        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }

        /**
         * @var  School $entry
         */
        if (isset($data['id'])) {
            $entry = School::find($data['id']);
            if (!$entry) {
                return [
                    'code' => 3,
                    'message' => 'Không tìm thấy',
                ];
            }

            $entry->fill($data);
            $entry->save();


            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
                'id' => $entry->id
            ];
        }
    }

    public function saveLicense(Request $req)
    {

        $dataContent = $req->all();
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');

        $rules = [
//            'label' => 'required|max:45',
//            'school_address' => 'required|max:255',
//            'school_email' => 'required|max:45|email',
//            'school_phone' => 'required|max:45',
//            'number_of_users' => 'required|integer|min:1',
//            'devices_per_user' => 'required|integer|min:1',
            'license_to' => 'required'
        ];

        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }

        /**
         * @var  School $entry
         */

        School::updateOrCreate(
            [
                'id' => $data['id'],
            ],
            [
                @'license_to' => @$data['license_to'],
                @'license_info' => @$data['license_info'],
                'license_state' => 1
            ]
        );

        return [
            'code' => 0,
            'message' => 'Đã cập nhật',
            'id' => $data['id']
        ];
    }


    /**
     * @param Request $req
     */
    public function toggleStatus(Request $req)
    {
        $id = $req->get('id');
        $entry = School::find($id);


        if (!$id) {
            return [
                'code' => 404,
                'message' => 'Not Found'
            ];
        }
        if ($entry->license_state == 0) {
            $entry->license_state = 1;
            $entry->save();
            return [
                'code' => 200,
                'message' => 'Đã Lưu',
                'object' => $entry->label,
                'status' => 'activated license'

            ];
        }
        if ($entry->license_state == 1) {
            $entry->license_state = 0;
            $entry->save();
            return [
                'code' => 200,
                'message' => 'Đã Lưu',
                'object' => $entry->label,
                'status' => 'deactivated license '

            ];
        }
//        $entry->license_state = $req->license_state ? 1 : 0;
//        $entry->save();

//        return [
//            'code' => 200,
//            'message' => 'Đã lưu'
//        ];
    }

    /**
     * Ajax data for index page
     * @uri  /xadmin/schools/data
     * @return  array
     */
    public function data(Request $req)
    {
        $user = Auth::user();
        $schoolAdmins = explode(',', $user->school_id);
        $schoolIdArrs = [];
        foreach ($schoolAdmins as $schoolAdmin) {
            if ($schoolAdmin) {
                $schoolIdArrs[] = (int)$schoolAdmin;
            }
        }
        $check = 0;
        foreach ($user->roles as $role) {
            if ($role->role_name == 'Super Administrator') {
                $check = 1;
            }
        }
        if ($check == 0) {
            $query = School::query()->whereIn('id', $schoolIdArrs)->with(['users', 'province', 'district']);


        } else {
            $query = School::query()->with(['users', 'province', 'district']);
            if ($req->role_name) {
                $admins = [];
                $nameAdmins = User::query()->with(['roles'])->where('full_name', 'LIKE', '%' . $req->role_name . '%')->get();
                foreach ($nameAdmins as $nameAdmin) {
                    foreach ($nameAdmin->roles as $role) {
                        if ($role->role_name == 'School Admin') {
                            $admins[] = $nameAdmin;
                        }
                    }
                }
                $explodeIds = [];
                foreach ($admins as $admin) {
                    $explodeIds[] = explode(',', @$admin->school_id);
                }

                foreach ($explodeIds as $explodeId) {
                    $query = School::query()->with(['users'])->whereIn('id', $explodeId)->orderBy('id', 'ASC');

                }
            }
        }
        if ($req->keyword) {
            $query->where('label', 'LIKE', '%' . $req->keyword . '%');
        }

        if ($req->label) {
            $query->where('label', 'LIKE', '%' . $req->label . '%');
        }
        if ($req->school_address) {
            $query->where('school_address', 'LIKE', '%' . $req->school_address . '%');
        }
        if ($req->province_id && $req->province_id != 'undefined' && $req->province_id != 'null') {
            $query->where('province_id', $req->province_id);
        }
        if ($req->district_id && $req->district_id != 'undefined' && $req->district_id != 'null') {
            $query->where('district_id', $req->district_id);
        }
//Sắp xếp
        if ($req->sortBy) {
            if ($req->sortBy == 'school_address' || $req->sortBy == 'license_to' || $req->sortBy == 'label' || $req->sortBy = 'devices_per_user') {
                if ($req->sortDirection == 1) {
                    $query->orderBy($req->sortBy, 'ASC');
                }

                if ($req->sortDirection == -1) {
                    $query->orderBy($req->sortBy, 'DESC');
                }

            }
            if ($req->sortBy == 'province') {
                $query->join('provinces', 'provinces.id', '=', 'school.province_id');
                if ($req->sortDirection == 1) {
                    $query->orderBy('provinces.name', 'ASC');
                }

                if ($req->sortDirection == -1) {
                    $query->orderBy('provinces.name', 'DESC');
                }
            }
            if ($req->sortBy == 'district') {
                $query->join('districts', 'districts.id', '=', 'school.district_id');
                if ($req->sortDirection == 1) {
                    $query->orderBy('districts.name', 'ASC');
                }

                if ($req->sortDirection == -1) {
                    $query->orderBy('districts.name', 'DESC');
                }
            }
        }


        $limit = 25;

        if ($req->limit) {
            $limit = $req->limit;
        }
        $data = [];
        $entries = $query->paginate($limit);
        $users = User::query()->with(['roles'])->whereNotNull('school_id')->orderBy('id', 'ASC')->get();

        foreach ($entries as $entry) {


            $nameSchoolAdmin = [];
            foreach ($users as $user) {
                $userSchools = explode(',', $user->school_id);
                $arrUserSchools = [];
                foreach ($userSchools as $userSchool) {
                    $arrUserSchools[] = (int)$userSchool;
                }


                foreach ($user->roles as $role) {
                    foreach ($arrUserSchools as $arrUserSchool) {
                        if ($role->role_name == 'School Admin' && $arrUserSchool == $entry->id) {
                            $nameSchoolAdmin[] = $user->full_name;
                        }
                    }


                }
            }
            $teacher = [];

            foreach ($entry->users as $user) {
                foreach ($user->roles as $role) {

                    if ($role->role_name == 'Teacher') {
                        $teacher[] = $user;
                    }

                }

            }
            $data[] = [
                'id' => $entry->id,
                'label' => $entry->label,
                'province' => @$entry->province->name,
                'district' => @$entry->district->name,
                'school_address' => $entry->school_address,
                'school_email' => $entry->school_email,
                'school_phone' => $entry->school_phone,
                'number_of_users' => $entry->number_of_users,
                'devices_per_user' => $entry->devices_per_user,
                'nameSchoolAdmin' => implode(' , ', $nameSchoolAdmin),
//                'license_info'=>$entry->license_info,
                'license_to' => $entry->license_to,
                'license_state' => $entry->license_state,
                'teacher' => $teacher,

            ];


        }
        return [
            'code' => 0,
            'data' => $data,
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
                'totalRecord' => $entries->count()
            ]
        ];
    }

    public function dataLicense(Request $req)
    {
        $query = School::query()->with(['users'])
            ->whereNotNull('license_to')
            ->orderBy('id', 'ASC');


        if ($req->keyword) {
            $query->where('label', 'LIKE', '%' . $req->keyword . '%');
        }

        if ($req->label) {
            $query->where('label', 'LIKE', '%' . $req->label . '%');
        }

        $limit = 25;

        if ($req->limit) {
            $limit = $req->limit;
        }
        $data = [];
        $entries = $query->paginate($limit);
        foreach ($entries as $entry) {
            $teacher = [];

            foreach ($entry->users as $user) {
                foreach ($user->roles as $role) {

                    if ($role->role_name == 'Teacher') {
                        $teacher[] = $user;
                    }
                }

            }
            $data[] = [
                'id' => $entry->id,
                'label' => $entry->label,
                'school_address' => $entry->school_address,
                'school_email' => $entry->school_email,
                'school_phone' => $entry->school_phone,
                'number_of_users' => $entry->number_of_users,
                'devices_per_user' => $entry->devices_per_user,
//                'license_info'=>$entry->license_info,
                'license_to' => $entry->license_to,
                'license_state' => $entry->license_state,
                'teacher' => $teacher,

            ];

        }
        return [
            'code' => 0,
            'data' => $data,
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
                'totalRecord' => $entries->count()
            ]
        ];
    }

    public function export()
    {
        $keys = [
            'school_name' => ['A', 'school_name'],
            'school_address' => ['B', 'school_address'],
            'school_email' => ['C', 'school_email'],
            'school_phone' => ['D', 'school_phone'],
            'license_info' => ['E', 'license_info'],
            'license_to' => ['F', 'license_to'],
            'license_state' => ['G', 'license_state'],
            'number_of_users' => ['H', 'number_of_users'],
            'devices_per_user' => ['I', 'devices_per_user'],
        ];

        $query = School::query()->orderBy('id', 'desc');

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
        $schools = School::query()->with(['users'])->whereIn('id', $ids)->get();
        $data = [];
        foreach ($schools as $school) {

            foreach ($school->users as $user) {

                foreach ($user->roles as $role) {

                    if ($role->role_name == 'Teacher' && $school->id == $user->school_id) {
                        $data[] = $user;
                    }
                }
            }
        }
        if ($data == []) {
            $check = 0;
            School::whereIn('id', $ids)->delete();
            return [
                'code' => 0,
                'check' => $check,
                'message' => 'Đã xóa'
            ];
        } else {
            $check = 1;
            return [
                'code' => 1,
                'check' => $check,
            ];
        }


    }

    public function licenseExpired()
    {
        $component = 'LicenseExpired';
        $title = 'License Expired';

        $jsonData = [

        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }

    public function getProvince()
    {
        $provinces = Province::orderBy('name', 'ASC')->with(['districts'])->get();
        $provinceData = [];

        foreach ($provinces as $province) {
            $districts = [];
            foreach ($province->districts as $district) {
                $districts[] = [
                    'id' => $district->id,
                    'label' => $district->name,
                ];
            }
            $provinceData[] = [
                'id' => $province->id,
                'label' => $province->name,
                'districts' => $districts
            ];
        }

        return $provinceData;
    }

    /**
     * Các chức năng về Import school
     */

    public function downloadTemplate(): BinaryFileResponse
    {
        return response()->download(public_path('sample/Import_School_Template.xlsx'));
    }

    public function validateImportSchool(Request $req)
    {
        $data = $req->all();
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
        $sheets = Excel::toCollection(new SchoolImport(), "{$y}/{$m}/{$hash}.{$extension}", 'excel-import');
        $schoolLists[] = $sheets;
        $validations = [];
        $error = [];
        $user = Auth::user();
        $code = 0;
        foreach ($schoolLists as $schoolList) {

            foreach ($schoolList as $schools) {

                foreach ($schools as $key => $school) {
                    if ($key > 8 && $school[0] != null) {
                        $item = [];
                        $item['label'] = $school[0];
                        $item['school_phone'] = $school[1];
                        $item['school_email'] = $school[2];
                        $item['devices_per_user'] = $school[3];
                        $item['number_of_users'] = $school[4];
                        $item['province'] = $school[5];
                        $item['district'] = $school[6];
                        $item['license_to'] = $school[7];
                        $validator = Validator::make($item, [
                            'label' => ['required','regex:/^[\p{L}\s\/0-9.,_-]+$/u'],
                            'devices_per_user' => ['required', 'numeric', 'gt:0'],
                            'number_of_users' => ['required', 'numeric', 'gt:0'],
                            'license_to' => ['required','date_format:d/m/Y']
                        ]);
                        $province = Province::query()->where('name', 'LIKE', '%' . $item['province'] . '%')->get();
                        if ($province) {
                            $district = District::query()->where('province_id', $province[0]->id)->where('name', 'LIKE', '%' . $item['district'] . '%')->get();
                        }
                        $validator->after(function ($validate) use ($province, $district) {
                            if ($province->count() == 0) {
                                $validate->errors()->add('province', 'City is not found');
                            }
                            if ($district->count() == 0) {
                                $validate->errors()->add('district', 'District is not found');

                            }
                        });
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
            if ($code == 2) {
                //export
                foreach ($validations as $key => $validation) {
                    if (@$validation['error']) {
                        {
                            $fileError[] = $validation;
                        }
                    } else {
                        $fileImport[] = $validation;
                    }
                }
                $errorName = 'export_teacher_error_' . uniqid(time());
                Cache::add($errorName, json_encode($fileError));
                return [
                    'code' => 2,
                    'fileImport' => $fileImport,
                    'fileError' => $fileError,
                    'errorFileName' => $errorName
                ];
            } else {
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
                    'fileImport' => $validations
                ];
            }

        }

    }

    public function exportErrorTeacher(Request $req)
    {
        $fileName = $req->fileError;
        $fileError = json_decode(Cache::get($fileName), true);
        Cache::forget($fileName);
        return Excel::download(new TeacherErrorExport($fileError), "File_import_teacher_error.xlsx");
    }

    public function import(Request $req)
    {
        $dataImport = $req->all();

        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }
        if ($dataImport['fileImport'] != []) {
            $user = Auth::user();
            foreach ($dataImport['fileImport'] as $import) {
                $user = new User();
                $user->username = $import['username'];
                $user->full_name = $import['full_name'];
                $user->phone = $import['phone'];
                $user->email = $import['email'];
                $user->class = $import['class'];
                $user->password = Hash::make($import['password']);
                $user->school_id = $dataImport['school_id'];
                $user->state = 1;
                $user->save();
                UserRole::create(['user_id' => $user->id, 'role_id' => 5]);

                $content = [
                    'full_name' => $import['full_name'],
                    'password' => $import['password'],
                    'username' => $import['username']
                ];
                dispatch(new SendMailPassword($import['email'], 'New account information', $content));
            }
            return [
                'code' => 0,
                'message' => 'Đã cập nhật '
            ];
        } else {
            return [
                'message' => 'Không có teacher nào được thêm'
            ];

        }
    }


}
