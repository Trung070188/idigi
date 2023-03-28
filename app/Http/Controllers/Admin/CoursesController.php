<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PermissionField;
use App\Models\AllocationContent;
use App\Models\AllocationContentUnit;
use App\Models\Lesson;
use App\Models\SchoolCourseUnit;
use App\Models\Unit;
use App\Models\UserUnit;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class CoursesController extends AdminBaseController
{
    public static $menus = [
        [
            'name' => 'Course',
            'icon' => 'fa fa-shopping-cart',
            'url' => '/xadmin/courses/index',
        ]
    ];

    /**
     * Index page
     * @uri  /xadmin/courses/index
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function index()
    {
        $title = 'Course';
        $component = 'CourseIndex';
        return component($component, compact('title'));
    }

    /**
     * Create new entry
     * @uri  /xadmin/courses/create
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function create(Request $req)
    {
        $component = 'CourseForm';
        $title = 'Create courses';
        return component($component, compact('title'));
    }

    /**
     * @uri  /xadmin/courses/edit?id=$id
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function edit(Request $req)
    {
        $id = $req->id;
        $entry = Course::with(['unit'])->where('id', $id)->first();

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        /**
         * @var  Course $entry
         */

        $title = 'Edit';
        $component = 'CourseDetail';

        $user = Auth::user();
        $permissionDetail = new PermissionField();
        $permissions = $permissionDetail->permission($user);
        $permissionFields = [];
        $permissionList = [
            'course_name',
            'course_subject',
            'course_grade',
            'course_description',
            'course_list_unit',
            'course_active',
            'course_delete'
        ];
        foreach ($permissionList as $permission) {
            $haspermission = $permissionDetail->havePermission($permission, $permissions, $user);
            $permissionFields[(string)$permission] = (bool)$haspermission;
        }
        $jsonData = [
            'entry'=>$entry,
            'permissionFields'=>$permissionFields
        ];


        return component($component, compact('title', 'jsonData'));
    }

    /**
     * @uri  /xadmin/courses/remove
     * @return  array
     */
    public function remove(Request $req)
    {
        $id = $req->id;
        $entry = Course::find($id);

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        $entry->delete();

        return [
            'code' => 0,
            'message' => 'Đã xóa'
        ];
    }

    public function removeCourse(Request $req)
    {

        foreach ($req->courseIds as $id) {
            $this->deleteAllocationByCourse($id);
        }
        Course::query()->whereIn('id', $req->courseIds)->update(['deleted_at' => Carbon::now()]);
        return [
            'code' => 0,
            'message' => 'Đã xóa'
        ];
    }

    /**
     * @uri  /xadmin/courses/save
     * @return  array
     */
    private function deleteAllocationByUnit($unitId, $courseId)
    {
        AllocationContentUnit::where('unit_id', $unitId)->where('course_id', $courseId)->delete();
        UserUnit::where('unit_id', $unitId)->where('course_id', $courseId)->delete();
        SchoolCourseUnit::where('unit_id', $unitId)->where('course_id', $courseId)->delete();
    }

    private function deleteAllocationByCourse($courseId)
    {
        AllocationContentUnit::where('course_id', $courseId)->delete();
        UserUnit::where('course_id', $courseId)->delete();
        SchoolCourseUnit::where('course_id', $courseId)->delete();
    }

    public function save(Request $req)
    {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');

        $rules = [
            'course_name' => ['required', 'max:100', 'regex:/^[\p{L}\s\/0-9.,?\(\)_:-]+$/u'],
            'subject' => 'required',
            'grade' => 'required',
            'description' => 'max:200'

        ];

        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }

        /**
         * @var  Course $entry
         */
        $unitIds = [];
        $message = 'Đã thêm';
        if (isset($data['id'])) {
            $entry = Course::where('id', $data['id'])->with(['unit1'])->first();
            if (!$entry) {
                return [
                    'code' => 3,
                    'message' => 'Không tìm thấy',
                ];
            }

            $entry->fill($data);
            if ($entry->unit1) {
                foreach ($entry->unit1 as $unit) {
                    $check = 0;
                    if ($req->units) {
                        foreach ($req->units as $key => $_unit) {
                            if($_unit['id'] == $unit->id){
                                $check = 1;
                            }
                        }
                    }
                    if($check == 0){
                        $this->deleteAllocationByUnit($unit->id, $unit->course_id);
                    }
                }
            }

            Unit::query()->where('course_id', $entry->id)->update(['course_id' => NULL]);
            if ($req->units) {
                foreach ($req->units as $key => $unit) {
                    $oldUnit = Unit::find($unit['id']);

                    if ($oldUnit->course_id != $entry->id) {
                        $this->deleteAllocationByUnit($oldUnit->id, $oldUnit->course_id);
                        $oldUnit->course_id = NULL;
                        $oldUnit->save();
                    }

                    $unitIds[] = $unit['id'];
                    Unit::query()->where('id', $unit['id'])->update(['course_id' => $entry->id, 'position' => $key + 1]);
                }

            }
            $entry->save();
            $message = 'Đã cập nhật';

        } else {
            $entry = new Course();
            $entry->fill($data);
            $entry->save();

            if ($req->units) {
                foreach ($req->units as $key => $unit) {
                    $unitIds[] = $unit['id'];

                    Unit::query()->where('id', $unit['id'])->update(['course_id' => $entry->id, 'position' => $key + 1]);

                }
            }
        }

        $lessons = Lesson::whereIn('unit_id', $unitIds)->get();

        foreach ($lessons as $key => $lesson) {

            $structure = json_decode($lesson->structure, true);
            $structure['grade'] = $entry->grade;
            $lesson->structure = json_encode($structure);

            $lesson->save();
        }

        return [
            'code' => 0,
            'message' => $message,
            'id' => $entry->id
        ];
    }

    /**
     * @param Request $req
     */
    public function toggleStatus(Request $req)
    {
        $id = $req->get('id');
        $entry = Course::find($id);

        if (!$id) {
            return [
                'code' => 404,
                'message' => 'Not Found'
            ];
        }

        $entry->active = $req->active ? 1 : 0;
        $entry->save();

        return [
            'code' => 200,
            'message' => 'Đã lưu'
        ];
    }

    /**
     * Ajax data for index page
     * @uri  /xadmin/courses/data
     * @return  array
     */
    public function data(Request $req)
    {
        $query = Course::query()->orderBy('id', 'desc');

        if ($req->keyword) {
            $query->where('course_name', 'LIKE', '%' . $req->keyword . '%');

        }
        if ($req->course_name) {
            $query->where('course_name', 'LIKE', '%' . $req->course_name . '%');
        }
        if ($req->subject) {
            $query->where('subject',  $req->subject);
        }
        if ($req->grade) {
            $query->where('grade', 'LIKE', '%' . $req->grade . '%');
        }
        if ($req->active != '') {
            $query->where('active', $req->active);
        }

        $query->createdIn($req->created);
        $count = $query->count();

        $limit = 25;
        if ($req->limit) {
            $limit = $req->limit;
        }
        $entries = $query->paginate($limit);

        return [
            'count' => $count,
            'code' => 0,
            'data' => $entries->items(),
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
            ]
        ];
    }

    public function dataCreateCourse(Request $req)
    {
        if ($req->subject) {
            $units = Unit::query()->where('subject', $req->subject)->orderBy('id', 'desc');

        } else {
            $units = Unit::query()->orderBy('id', 'desc');

        }
        return [
            'units' => $units->get(),
        ];
    }

    public function getCourses()
    {
        $courses = Course::orderBy('course_name', 'ASC')->get();

        return $courses;
    }

    public function dataEditCourse(Request $req)
    {
        if ($req->subject) {
            $units = Unit::query()->where('subject', $req->subject)->orderBy('id', 'desc');

        } else {
            $units = Unit::query()->orderBy('id', 'desc');

        }
        $listUnit = Unit::query()->where('course_id', $req->id)->orderBy('position', 'ASC')->get();

        return [
            'units' => $units->get(),
            'listUnit' => $listUnit
        ];
    }

    public function export()
    {
        $keys = [
            'name' => ['A', 'name'],
            'public_from' => ['B', 'public_from'],
            'public_to' => ['C', 'public_to'],
            'status' => ['D', 'status'],
        ];

        $query = Course::query()->orderBy('id', 'desc');

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
