<?php

namespace App\Http\Controllers\Admin;


use App\Models\UserCourseUnit;
use App\Models\UserUnit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\AllocationContent;
use App\Models\AllocationContentCourse;
use App\Models\AllocationContentSchool;
use App\Models\AllocationContentUnit;
use App\Models\Course;
use App\Models\School;
use App\Models\SchoolCourse;
use App\Models\SchoolCourseUnit;
use App\Models\Unit;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class AllocationContentsController extends AdminBaseController
{
    public static $menus = [
        [
            'name' => 'AllocationContent',
            'icon' => 'fa fa-shopping-cart',
            'url' => '/xadmin/allocation_contents/index',
        ]
    ];

    /**
     * Index page
     * @uri  /xadmin/allocation_contents/index
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function index() {
        $title = 'AllocationContent';
        $component = 'Allocation_contentIndex';
        return component($component, compact('title'));
    }

    /**
     * Create new entry
     * @uri  /xadmin/allocation_contents/create
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function create (Request $req) {
        $component = 'Allocation_contentForm';
        $title = 'Create allocation_contents';
        $schools=School::query()->orderBy('id','desc')->get();
        $courses=Course::query()->with(['unit'])->orderBy('id','desc')->get();
        $units=Unit::query()->orderBy('id','desc')->get();
        foreach ($courses as $course)
        {


            $course['units']=[];
            $unit=[];

            foreach ($course->unit as $courseUnit)
            {
                if($courseUnit->course_id==$course->id)
                {
                    $unit[]=$courseUnit;

                }
            }
            $course['units']=$unit;

        }
        $jsonData=[
            'schools'=>$schools,
            'courses'=>$courses,
            'units'=>$units,
        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }

    /**
     * @uri  /xadmin/allocation_contents/edit?id=$id
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function edit (Request $req) {
        $data=$req->all();
        $id = $req->id;
        $entry = AllocationContent::query()->with(['schools','courses','units','course_unit'])->where('id',$id)->first();
        $courses=Course::query()->with(['units','unit'])->orderBy('id','ASC')->get();
        $units=Unit::query()->orderBy('id','desc')->get();
        $total_units=($entry->units);
        $total_schools=$entry->schools;
        $total_courses=$entry->courses;
        $course_unit=$entry->course_unit;
        $totalSchoolArray=[];
        if($total_schools)
        {
            foreach($total_schools as $total_school)
            {

                $totalSchoolArray[]=$total_school->id;
            }
        }
        $totalCourseArray=[];
        if($total_courses)
        {

            foreach($total_courses as $total_course)
            {
                $totalCourseArray[]=$total_course->id;
            }
        }
        if($total_courses)
        {

            foreach($courses as $course)
            {

                $course['total_unit']=[];
                $total_unit=[];
                foreach($course_unit as $un)
                {
                    if($un->course_id==$course->id)
                    {
                        $total_unit[]=$un->unit_id;
                    }
                }
                $course['total_unit']=$total_unit;
            }
        }

        $schools=School::query()->orderBy('id','desc')->get();

        if (!$entry) {
            throw new NotFoundHttpException();
        }
        /**
         * @var  AllocationContent $entry
         */
        $jsonData=[
            'totalSchoolArray'=>$totalSchoolArray,
            'totalCourseArray'=>$totalCourseArray,
            'entry'=>$entry,
            'total_cousers'=>$total_courses,
            'schools'=>$schools,
            'courses'=>$courses,
            'units'=>$units,
            'total_unit'=>$total_unit,
        ];
        $title = 'Edit';
        $component = 'Allocation_contentEdit';


        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }

    /**
     * @uri  /xadmin/allocation_contents/remove
     * @return  array
     */
    public function remove(Request $req) {
        $id = $req->id;
        $entry = AllocationContent::find($id);

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        $entry->delete();

        return [
            'code' => 0,
            'message' => 'Đã xóa',
            'actionName'=>$entry->title,
            'status'=>'deleted content allocation'
        ];
    }

    /**
     * @uri  /xadmin/allocation_contents/save
     * @return  array
     */
    public function save(Request $req) {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');
        $dataContent=$req->all();

        $rules = [
            'title' => 'required',
            // 'total_school' => 'max:1000',
            // 'total_course' => 'max:1000',
            // 'total_unit' => 'max:1000',
            // 'status' => 'numeric',
        ];
        if($dataContent['total_course']==[])
        {

            $rules['total_course'] = ['required'];
        }
        if($dataContent['total_course'])
        {
            foreach($dataContent['total_course'] as $total_course)
            {

                foreach($dataContent['unit'] as $unit)
                {
                    if($unit['id']==$total_course)
                    {
                        if(!@$unit['total_unit']  )
                        {
                            $rules['total_unit'] = ['required'];
                        }
                        if(@$unit['total_unit']==[])
                        {
                            $rules['total_unit'] = ['required'];
                        }
                    }

                }
            }

        }
        $customMessages = [
            'total_course.required' => 'The course field is required.',
            'total_unit.required'=>'The unit field is required.'
        ];

        $v = Validator::make($data, $rules,$customMessages);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }


        /**
         * @var  AllocationContent $entry
         */
        if (isset($data['id'])) {
            $entry = AllocationContent::find($data['id']);
            if (!$entry) {
                return [
                    'code' => 3,
                    'message' => 'Không tìm thấy',
                ];
            }

            $entry->fill($data);
            $entry->save();
            AllocationContentSchool::where('allocation_content_id',$entry->id)->delete();
            if(@$dataContent['total_school'])
            {
                foreach($dataContent['total_school'] as $schoolId)
                {

                    $contentSchools=AllocationContentSchool::query()->orderBy('allocation_content_id','desc')->get();
                    foreach ( $contentSchools as  $contentSchool)
                    {
                        if($contentSchool->school_id==$schoolId)
                        {
                            return [
                                'code' => 3,
                                'message' =>'Trường đã xuất hiện trong content khác',
                            ];
                        }
                    }
                    AllocationContentSchool::create(['school_id'=>$schoolId,'allocation_content_id'=>$entry->id]);



                }

            }

            AllocationContentCourse::where('allocation_content_id',$entry->id)->delete();


            AllocationContentUnit::where('allocation_content_id',$entry->id)->delete();

            if(@$dataContent['total_course'])
            {


                foreach($dataContent['total_course'] as $courseId)
                {
                    AllocationContentCourse::create(['course_id'=>$courseId,'allocation_content_id'=>$entry->id]);
                }
                $contentCourses=AllocationContentCourse::where('allocation_content_id',$entry->id)->get();
                $deleteCourses=[];
                    foreach($contentCourses as $contentCourse)
                {

                    {
                        $deleteCourses[]=$contentCourse->course_id;
                    }

                }

                // code cập nhật bảng school_course khi thay đổi content ở bảng allocation_content_course
                $contentUpdates=SchoolCourse::query()->where('allocation_content_id',$entry->id)->get();
                foreach($contentUpdates as $contentUpdate)
                {
                    SchoolCourse::where('allocation_content_id',$entry->id)->delete();

                    foreach($deleteCourses as $deleteCourse)
                    {
                        SchoolCourse::create(['allocation_content_id'=>$entry->id,'school_id'=>$contentUpdate->school_id,'course_id'=>$deleteCourse]);

                    }

                }

                $deleteCourseUsers=[];
                foreach ($contentUpdates as $contentUpdate)
                {
                    $deleteCourseUsers[]=$contentUpdate->course_id;
                }
                UserCourseUnit::where('allocation_content_id',$entry->id)->whereNotIn('course_id',$deleteCourseUsers)->delete();

                if(@$dataContent['unit'])
                {

                    foreach($dataContent['unit'] as $course)
                    {

                        if(@$course['total_unit'])
                        {
                            foreach($course['total_unit'] as $unitId)
                            {
                                if(in_array($course['id'], $dataContent['total_course'])){
                                    AllocationContentUnit::create(['course_id'=>$course['id'],'allocation_content_id'=>$entry->id,'unit_id'=>$unitId]);
                                }
                            }

                        }

                    }
                    // code cập nhật bảng school_course_unit khi thay đổi content ở bảng allocation_content_course
                    $contentCourseUnits=AllocationContentUnit::where('allocation_content_id',$entry->id)->get();
                    $contentUnitUpdates=SchoolCourseUnit::query()->where('allocation_content_id',$entry->id)->get();
                    foreach($contentUnitUpdates as $contentUnitUpdate)
                    {
                        SchoolCourseUnit::where('allocation_content_id',$entry->id)->delete();

                        foreach($contentCourseUnits as $contentCourseUnit)
                        {
                            SchoolCourseUnit::create(['allocation_content_id'=>$entry->id,'school_id'=>$contentUnitUpdate->school_id,'course_id'=>$contentCourseUnit->course_id,'unit_id'=>$contentCourseUnit->unit_id]);

                        }

                    }
                    $deleteUnitUsers=[];
                    foreach ($contentCourseUnits as $contentCourseUnit)
                    {
                        $deleteUnitUsers[]=$contentCourseUnit->unit_id;
                    }
                  UserUnit::where('allocation_content_id',$entry->id)->whereNotIn('unit_id',$deleteUnitUsers)->delete();



                }

            }

            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
                'id' => $entry->id,
                'actionName'=>$entry->title,
                'status'=>'edited content allocation'

            ];
        } else {
            $entry = new AllocationContent();
            $courses=Course::query()->orderBy('id','desc')->get();

            $entry->fill($data);
            $entry->save();


            foreach($dataContent['total_school'] as $schoolId)
            {
                AllocationContentSchool::create(['school_id'=>$schoolId,'allocation_content_id'=>$entry->id]);
            }
            foreach($dataContent['total_course'] as $courseId)
            {

                AllocationContentCourse::create(['course_id'=>$courseId,'allocation_content_id'=>$entry->id]);

            }
            foreach($dataContent['unit'] as $unit)
            {
                {
                    if(@$unit['total_unit'])
                    {
                        foreach($unit['total_unit'] as $unitId)
                        {

                            AllocationContentUnit::create(['course_id'=>$unit['id'],'allocation_content_id'=>$entry->id,'unit_id'=>$unitId]);
                        }
                    }

                }

            }
            return [
                'code' => 0,
                'message' => 'Đã thêm',
                'id' => $entry->id,
                'actionName'=>$entry->title,
                'status'=>'created new content allocation'
            ];
        }
    }

    /**
     * @param  Request $req
     */
    public function toggleStatus(Request $req)
    {
        $id = $req->get('id');
        $entry = AllocationContent::find($id);

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
     * @uri  /xadmin/allocation_contents/data
     * @return  array
     */
    public function data(Request $req) {
        $query = AllocationContent::query()->with(['courses','units','schools'])->orderBy('id', 'desc');

        if ($req->keyword) {
            $query->where('title', 'LIKE', '%' . $req->keyword. '%');
        }

        $query->createdIn($req->created);

        $limit = 25;
        if ($req->limit) {
            $limit = $req->limit;
        }
        $entries = $query->paginate($limit);
        return [
            'code' => 0,
            'data' => $entries->items(),
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
                'totalRecord' => $entries->count(),

            ]
        ];
    }

    public function export() {
        $keys = [
            'title' => ['A', 'title'],
            'total_school' => ['B', 'total_school'],
            'total_course' => ['C', 'total_course'],
            'total_unit' => ['D', 'total_unit'],
            'status' => ['E', 'status'],
        ];

        $query = AllocationContent::query()->orderBy('id', 'desc');

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
