<?php

namespace App\Http\Controllers\Admin;


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
        $courses=Course::query()->orderBy('id','desc')->get();
        $units=Unit::query()->orderBy('id','desc')->get();
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
        $entry = AllocationContent::query()->with(['schools','courses','units','coursess'])->where('id',$id)->first();
        $total_schools=$entry->schools;
        $total_courses=$entry->courses;
        dd($entry->coursess);
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
        $totalUnitArray=[];
        
        
        $schools=School::query()->orderBy('id','desc')->get();
        $courses=Course::query()->orderBy('id','desc')->get();
        
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
            'schools'=>$schools,
            'courses'=>$courses
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
            'message' => 'Đã xóa'
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
    'title' => 'max:191',
    'total_school' => 'max:1000',
    'total_course' => 'max:1000',
    'total_unit' => 'max:1000',
    'status' => 'numeric',
];

        $v = Validator::make($data, $rules);

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
            AllocationContentCourse::where('allocation_content_id',$entry->id)->delete();

            foreach($dataContent['total_school'] as $schoolId)
            {
                AllocationContentSchool::create(['school_id'=>$schoolId,'allocation_content_id'=>$entry->id]);
            }
            foreach($dataContent['total_course'] as $courseId)
            {
                AllocationContentCourse::create(['course_id'=>$courseId,'allocation_content_id'=>$entry->id]);
            }

            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
                'id' => $entry->id
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
               
                foreach($dataContent['total_unit'] as $unitID)
            {
              
                AllocationContentUnit::create(['allocation_content_id'=>$entry->id,'course_id'=>$courseId,'unit_id'=>$unitID]);
            }
            }
            

            return [
                'code' => 0,
                'message' => 'Đã thêm',
                'id' => $entry->id
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
        $query = AllocationContent::query()->orderBy('id', 'desc');

        if ($req->keyword) {
            //$query->where('title', 'LIKE', '%' . $req->keyword. '%');
        }

        $query->createdIn($req->created);


        $entries = $query->paginate();

        return [
            'code' => 0,
            'data' => $entries->items(),
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
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
