<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PermissionField;
use App\Models\AllocationContent;
use App\Models\AllocationContentSchool;
use App\Models\Course;
use App\Models\SchoolCourse;
use App\Models\SchoolCourseUnit;
use App\Models\User;
use App\Models\UserCourseUnit;
use App\Models\UserUnit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\School;
use App\Models\Unit;
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
        $allocationContens = AllocationContent::query()->with(['course_unit', 'courses','units'])->orderBy('id', 'desc')->get()->toArray();
        //cap nhat content tu dong
        $newAllocationContents = [];
        foreach ($allocationContens as $allocationContent){
            $units=$allocationContent['units'];
        // khoi tao array Course
            $arrayCourse = [];

            if(@$allocationContent['courses']){

                foreach ($allocationContent['courses'] as $course){
                    $newCourse = $course;

                    $newCourse['total_unit'] = [];
                    if(@$allocationContent['course_unit']){
                        foreach (@$allocationContent['course_unit'] as $courseUnit){
                            if($course['id'] == $courseUnit['course_id']){
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
            'arrayCourse'=> $arrayCourse,
            'units'=>$units,


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
        @$schoolName = $user->schools->label;
        foreach ($user->roles as $role) {
            @$roleName = $role->role_name;
        }

        return [
            'code' => 0,
            'schoolName' => $schoolName,
            'roleName' => $roleName
        ];


    }

    public function dataTeacher(Request $req)
    {
        $id = $req->id;
        $entry = School::find($id);
        $query = User::query()
            ->with(['roles', 'user_devices'])
            ->where('school_id', '=', $entry->id)
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
            'lengthDeviceTeacher'=>$entry->devices_per_user,
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
        $jsonData = [
            'entry' => $entry,
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
        $entry = School::with(['allocation_contents', 'school_courses', 'school_course_units', 'allocation_school','users'])->where('id', $id)->first();
        $allocationContents = AllocationContent::query()->with(['course_unit', 'courses','units'])->orderBy('id', 'desc')->get()->toArray();
        $lengthTeacher=0;
        if(@$entry->users)
        {
            $teacher=[];
            foreach ($entry->users as $user)
            {
                if(@$user->roles)
                {
                    foreach ($user->roles as $role)
                    {
                        if($role->role_name=='Teacher')
                        {
                            $teacher[]=$user;

                        }
                    }
                }
        }

            $lengthTeacher=count($teacher);
        }


        //load content tu dong
        $newAllocationContents = [];
        foreach ($allocationContents as $allocationContent){

            $arrayCourse = [];

            if(@$allocationContent['courses']){

                foreach ($allocationContent['courses'] as $course){
                    $newCourse = $course;

                    $newCourse['total_unit'] = [];
                    if(@$allocationContent['course_unit']){
                        foreach (@$allocationContent['course_unit'] as $courseUnit){
                            if($course['id'] == $courseUnit['course_id']){
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
        $courses=null;
        if($entry->allocation_contents)
        {
            foreach ($allocationContentSchools as $allocationContentSchool) {

                $courses = ($allocationContentSchool->courses);
                $course_unit = $allocationContentSchool->course_unit;
                if($allocationContentSchool->courses)
                {
                    foreach ($courses as $course) {
                        $course['unit']=$course->unit;

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

        if(@$entry->school_courses)
        {
            $courses2=$entry->school_courses;
            $course_unit2 = $entry->school_course_units;
            foreach ( $courses2 as $course2)
            {
                $course2['unit']=$course2->unit;

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
        $permissionDetail = new PermissionField();
        $permissions = $permissionDetail->permission($user);
        $permissionFields = [
            'school_name' => $permissionDetail->havePermission('school_name',$permissions,$user),
            'school_address'=>$permissionDetail->havePermission('school_address',$permissions,$user),
            'school_email'=>$permissionDetail->havePermission('school_email',$permissions,$user),
            'school_phone_number'=>$permissionDetail->havePermission('school_phone_number',$permissions,$user),
            'school_device'=>$permissionDetail->havePermission('school_device',$permissions,$user),
            'school_user'=>$permissionDetail->havePermission('school_user',$permissions,$user),
            'school_expire_date'=>$permissionDetail->havePermission('school_expire_date',$permissions,$user),
            'school_description'=>$permissionDetail->havePermission('school_description',$permissions,$user),
            'school_content'=>$permissionDetail->havePermission('school_content',$permissions,$user),

        ];
        $title = 'Edit';
        $component = 'SchoolEdit';
        $entry->allocationContentId = $allocationContentId;
        $jsonData = [
            'permissionFields'=>$permissionFields,
            'teacher'=>$lengthTeacher,
            'entry' => $entry,
            @'allocationContents' => @$newAllocationContents,
            @'allocationContentSchoolName' =>@ $allocationContentSchoolName,
            'allocationContentId' => $allocationContentId,
            'courses' =>$courses,
            @'units' => @$units,
            @'courses2'=>@$courses2
        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }

    /**
     * @uri  /xadmin/schools/remove
     * @return  array
     */
    public function remove(Request $req)
    {
        $id = $req->id;
        $entry = School::find($id);

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        $entry->delete();

        return [
            'code' => 0,
            'message' => 'Đã xóa',
            'actionName'=>$entry->label,
            'status'=>'deleted school'
        ];
    }
    public function removeLicense(Request $req)
    {
        $id = $req->id;
        $entry = School::find($id);
       School::where('license_to',$entry->license_to)->delete();

        if (!$entry) {
            throw new NotFoundHttpException();
        }
        School::updateOrCreate(
            [
                'id'=>$entry->id,
            ],
            [

                'label'=>$entry->label,
                'school_email'=>$entry->school_email,
                'school_phone'=>$entry->school_phone,
                'school_description'=>$entry->school_description,
                'number_of_users'=>$entry->number_of_users,
                'devices_per_user'=>$entry->devices_per_user,
                'license_state'=>0

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
        $dataContent = $req->all();
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');

        $rules = [
            'label' => 'required|max:45',
            'school_address' => 'required|max:255',
            'number_of_users' => 'required|min:1',
            'devices_per_user' => 'required|min:1',
            'license_to'=>'required'
        ];
        if(@$data['school_email'])
        {
            $rules=[
                'school_email' => 'email',
            ];
        }
        if(@$data['school_phone'])
        {
            $rules=[
                'school_phone' => 'min:10',
            ];
        }

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
            AllocationContentSchool::where('school_id', $entry->id)->delete();
            SchoolCourseUnit::where('school_id', $entry->id)->delete();
            $userUnits=UserUnit::query()->where('school_id','=',$entry->id)->get();
            foreach ($userUnits as $userUnit)
            {
                if($userUnit->allocation_content_id!=$dataContent['allocationContentSchool'])
                {
                    UserUnit::where('school_id',$entry->id)->delete();
                    UserCourseUnit::where('school_id',$entry->id)->delete();
                }
            }
            if (@$dataContent['allocationContentSchool']) {
                AllocationContentSchool::updateOrCreate(
                  [
                      'school_id'=>$entry->id,
                  ]  ,
                    [
                        'allocation_content_id'=>$dataContent['allocationContentSchool']
                    ]
                );
//                AllocationContentSchool::create(['allocation_content_id' => $dataContent['allocationContentSchool'], 'school_id' => $entry->id]);

            }
            SchoolCourseUnit::where('school_id',$entry->id)->delete();
            foreach ($entry->allocation_contents as $contents) {
                foreach ($contents->course_unit as $schoolCourse) {
                    SchoolCourseUnit::create(['school_id' => $entry->id, 'course_id' => $schoolCourse->course_id, 'unit_id' => $schoolCourse->unit_id,'allocation_content_id'=>$dataContent['allocationContentSchool']]);
                }
            }
            SchoolCourse::where('school_id',$entry->id)->delete();
            foreach ($entry->allocation_contents as $contents) {
                foreach ($contents->courses as $schoolCourse) {
                    SchoolCourse::create(['school_id' => $entry->id, 'course_id' => $schoolCourse->id,'allocation_content_id'=>$dataContent['allocationContentSchool']]);
                }
            }

            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
                'id' => $entry->id,
                'actionName'=>$entry->label,
                'status'=>'edited school',
            ];
        } else {
            $entry = new School();
            $entry->fill($data);
            $entry->save();
            if (@$dataContent['allocationContenSchool']) {
                AllocationContentSchool::create(['allocation_content_id' => $dataContent['allocationContenSchool'], 'school_id' => $entry->id]);

                foreach ($entry->allocation_contents as $contents) {
                    foreach ($contents->course_unit as $schoolCourse) {
                        SchoolCourseUnit::create(['school_id' => $entry->id, 'course_id' => $schoolCourse->course_id, 'unit_id' => $schoolCourse->unit_id,'allocation_content_id'=>$dataContent['allocationContenSchool']]);
                    }
                }

                foreach ($entry->allocation_contents as $contents) {
                    foreach ($contents->courses as $schoolCourse) {
                        SchoolCourse::create(['school_id' => $entry->id, 'course_id' => $schoolCourse->id,'allocation_content_id'=>$dataContent['allocationContenSchool']]);
                    }
                }
            }

            return [
                'code' => 0,
                'message' => 'Đã thêm',
                'id' => $entry->id,
                'actionName'=>$entry->label,
                'status'=>'created new school'
            ];
        }
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
          'license_to'=>'required',
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
            'license_to'=>'required'
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
                'id'=>$data['id'],
            ],
            [
                @'license_to'=>@$data['license_to'],
                @'license_info'=>@$data['license_info'],
                'license_state'=>1
            ]
        );

        return [
            'code' => 0,
            'message' => 'Đã cập nhật',
            'id' =>$data['id']
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
        if($entry->license_state == 0)
        {
            $entry->license_state = 1;
            $entry->save();
            return [
                'code' => 200,
                'message' => 'Đã Lưu',
                'actionName'=>$entry->label,
                'status'=>'activated license'

            ];
        }
        if($entry->license_state == 1)
        {
            $entry->license_state = 0;
            $entry->save();
            return [
                'code' => 200,
                'message' => 'Đã Lưu',
                'actionName'=>$entry->label,
                'status'=>'deactivated license '

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
        $query = School::query()->with(['users'])->orderBy('id', 'ASC');


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
        $users=User::query()->with(['roles'])->whereNotNull('school_id')->orderBy('id','ASC')->get();
        $userAdminSchools=[];
        foreach($users as $user)
        {
            foreach($user->roles as $role)
            {
               if($role->role_name=='School Admin')
               {
                   $userAdminSchools[]=$user;
               }
            }
        }
        foreach ($entries as $entry) {
            $teacher = [];
            $nameSchoolAdmin='';

            foreach($userAdminSchools as $userAdminSchool )
            {
                if($userAdminSchool->school_id==$entry->id)
                {
                    $nameSchoolAdmin=$userAdminSchool->full_name;
                }
            }

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
                'nameSchoolAdmin'=>$nameSchoolAdmin,
//                'license_info'=>$entry->license_info,
               'license_to'=>$entry->license_to,
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
        $schools=School::query()->with(['users'])->whereIn('id',$ids)->get();
        $data=[];
        foreach ($schools as $school)
        {

            foreach ($school->users as $user)
            {

                foreach ($user->roles as $role)
                {

                    if($role->role_name=='Teacher' && $school->id==$user->school_id )
                    {
                        $data[]=$user;
                    }
                }
            }
        }
       if($data==[])
       {
           $check=0;
           School::whereIn('id', $ids)->delete();
           return [
               'code' => 0,
               'check'=>$check,
               'message' => 'Đã xóa'
           ];
       }
       else{
           $check=1;
           return [
               'code'=>1,
               'check'=>$check,
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
}
