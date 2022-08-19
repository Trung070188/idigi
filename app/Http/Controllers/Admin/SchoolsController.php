<?php

namespace App\Http\Controllers\Admin;

use App\Models\AllocationContent;
use App\Models\AllocationContentSchool;
use App\Models\SchoolCourse;
use App\Models\User;
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
        $allocationContens = AllocationContent::query()->orderBy('id', 'desc')->get();
        $jsonData = [
            'allocationContens' => $allocationContens,
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
        $entry = School::with(['allocation_contens', 'school_courses', 'courses', 'units', 'allocation_school'])->where('id', $id)->first();
        $allocationContens = AllocationContent::query()->with(['course_unit', 'courses'])->orderBy('id', 'desc')->get();

        //            $courses=$entry->courses;
//
//            foreach ($courses as $course)
//            {
//                $units=$course->units;
//                $course['total_unit']=[];
//                $total_unit=[];
//                foreach ($entry->school_courses as $choolCourse)
//                {
//                    if($course->id==$choolCourse->course_id)
//                    {
//                        $total_unit[]=$choolCourse->unit_id;
//
//                    }
//                    $course['total_unit']=$total_unit;
//                }
//            }
        $allocationContenSchools = $entry->allocation_contens;
        $allocationContentId = @$entry->allocation_school->allocation_content_id;
        foreach ($allocationContenSchools as $allocationContenSchool) {
            $courses = ($allocationContenSchool->courses);
            $course_unit = $allocationContenSchool->course_unit;
            foreach ($courses as $course) {

                $course['total_unit'] = [];
                $total_unit = [];
                foreach ($course_unit as $un) {

                    if ($un->course_id == $course->id) {
                        $total_unit[] = $un->unit_id;
                    }
                }
                @$course['total_unit'] = $total_unit;


            }
            $units = ($allocationContenSchool->units);
        }
        foreach ($allocationContenSchools as $allocationContenSchool) {
            @$allocationContenSchoolName = $allocationContenSchool->title;
        }

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        /**
         * @var  School $entry
         */

        $title = 'Edit';
        $component = 'SchoolEdit';
        $entry->allocationContentId = $allocationContentId;
        $jsonData = [
            'entry' => $entry,
            @'allocationContens' => @$allocationContens,
            @'allocationContenSchoolName' => @$allocationContenSchoolName,
            @'allocationContentId' => @$allocationContentId,
            @'courses' => @$courses,
            @'units' => @$units,
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
            'message' => 'Đã xóa'
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
            'school_email' => 'email',
            'school_phone' => 'max:45',
            'number_of_users' => 'required|integer|min:1',
            'devices_per_user' => 'required|integer|min:1',
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
            AllocationContentSchool::where('school_id', $entry->id)->delete();
            if (@$dataContent['allocationContenSchool']) {
                AllocationContentSchool::create(['allocation_content_id' => $dataContent['allocationContenSchool'], 'school_id' => $entry->id]);

            }
            SchoolCourse::where('school_id', $entry->id)->delete();
            foreach ($entry->allocation_contens as $contents) {
                foreach ($contents->course_unit as $schoolCourse) {
                    SchoolCourse::create(['school_id' => $entry->id, 'course_id' => $schoolCourse->course_id, 'unit_id' => $schoolCourse->unit_id]);
                }
            }


            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
                'id' => $entry->id
            ];
        } else {
            $entry = new School();
            $entry->fill($data);
            $entry->save();
            if (@$dataContent['allocationContenSchool']) {
                AllocationContentSchool::create(['allocation_content_id' => $dataContent['allocationContenSchool'], 'school_id' => $entry->id]);
            }


            return [
                'code' => 0,
                'message' => 'Đã thêm',
                'id' => $entry->id
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

        $entry->status = $req->status ? 1 : 0;
        $entry->save();

        return [
            'code' => 200,
            'message' => 'Đã lưu'
        ];
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
//                'license_to'=>$entry->license_to,
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
        School::whereIn('id', $ids)->delete();
        return [
            'code' => 0,
            'message' => 'Đã xóa'
        ];
    }
}
