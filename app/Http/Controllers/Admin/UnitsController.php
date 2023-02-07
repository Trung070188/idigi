<?php

namespace App\Http\Controllers\Admin;


use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonInventory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Unit;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class UnitsController extends AdminBaseController
{
    public static $menus = [
        [
            'name' => 'Unit',
            'icon' => 'fa fa-shopping-cart',
            'url' => '/xadmin/units/index',
        ]
    ];

    /**
    * Index page
    * @uri  /xadmin/units/index
    * @throw  NotFoundHttpException
    * @return  View
    */
    public function index() {
        $title = 'Unit';
        $component = 'UnitIndex';
        return component($component, compact('title'));
    }

    /**
    * Create new entry
    * @uri  /xadmin/units/create
    * @throw  NotFoundHttpException
    * @return  View
    */
    public function create (Request $req) {
        $component = 'UnitForm';
        $title = 'Create units';
        return component($component, compact('title'));
    }

    /**
    * @uri  /xadmin/units/edit?id=$id
    * @throw  NotFoundHttpException
    * @return  View
    */
    public function edit (Request $req) {
        $id = $req->id;
        $entry = Unit::find($id);

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        /**
        * @var  Unit $entry
        */

        $title = 'Edit';
        $component = 'UnitDetail';


        return component($component, compact('title', 'entry'));
    }

    /**
    * @uri  /xadmin/units/remove
    * @return  array
    */
    public function remove(Request $req) {
        $id = $req->id;
        $entry = Unit::find($id);

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
    * @uri  /xadmin/units/save
    * @return  array
    */
    public function save(Request $req) {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');

        $rules = [
    'label' => 'required|max:191',
    'subject'=>'required'
];
        $message =[
          'label.required'=>'The unit name field is required'
        ];

        $v = Validator::make($data, $rules,$message);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }

        /**
        * @var  Unit $entry
        */
        if (isset($data['id'])) {
            $entry = Unit::find($data['id']);
            if (!$entry) {
                return [
                    'code' => 3,
                    'message' => 'Không tìm thấy',
                ];
            }
            if($entry->course_id!=$data['course_id'])
            {
                $dsUnit=Unit::query()->where('course_id',$data['course_id'])->count();
                $entry->fill($data);
                $entry->position=($dsUnit+1);
            }
            else {
                $entry->fill($data);
            }
            foreach ($req->list as $lesson)
            {
                Lesson::query()->where('id',$lesson['id'])->update(['unit_id'=>$entry->id,
                    'unit_name'=>$entry->unit_name,
                    'course_id'=>$entry->course_id,
                ]);
            }
            $entry->save();

            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
                'id' => $entry->id
            ];
        } else {
            $entry = new Unit();
            $entry->fill($data);
            $dsUnit=Unit::query()->where('course_id',$entry->course_id)->count();
            $entry->position=($dsUnit+1);
            $entry->save();
            foreach ($req->list as $lesson)
            {
                Lesson::query()->where('id',$lesson['id'])->update(['unit_id'=>$entry->id,
                    'unit_name'=>$entry->unit_name,
                    'course_id'=>$entry->course_id,
                ]);
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
        $entry = Unit::find($id);

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
    * @uri  /xadmin/units/data
    * @return  array
    */
    public function data(Request $req)
    {
        $query = Unit::query()->orderBy('id', 'desc');
        $courses = Course::query()->orderBy('id', 'desc')->get();

        if ($req->keyword) {
            $query->where('unit_name', 'LIKE', '%' . $req->keyword . '%')
                ->orWhere('subject', 'LIKE', '%' . $req->subject . '%');
        }
        if ($req->course_name) {
            $query->where('unit_name', 'LIKE', '%' . $req->course_name . '%');
        }
        if ($req->subject) {
            $query->where('subject', 'LIKE', '%' . $req->subject . '%');
        }
        if ($req->course_id)
        {
            $query->where('course_id','LIKE','%'.$req->course_id . '%');
        }

        $query->createdIn($req->created);
        $limit = 25;
        if ($req->limit) {
            $limit = $req->limit;
        }
        $entries = $query->paginate($limit);

        return [
            'code' => 0,
            'courses'=>$courses,
            'count'=>$query->count(),
            'data' => $entries->items(),
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
            ]
        ];
    }
    public function dataCreateUnit(Request $req)
    {
        $lessons=Lesson::query()->orderBy('id','desc');
        $courses=Course::query()->orderBy('id','desc')->get();

        return [
          'lessons'=>$lessons->get(),
            'courses'=>$courses
        ];
    }
    public function dataEditUnit(Request $req)
    {
        $listLessons=Lesson::query()->where('unit_id',$req->id)->get();
        $lessons=Lesson::query()->orderBy('id','desc');
        $courses=Course::query()->orderBy('id','desc')->get();

        return [
            'lessons'=>$lessons->get(),
            'list_lessons'=>$listLessons,
            'courses'=>$courses

        ];
    }

    public function export() {
                $keys = [
                            'label' => ['A', 'label'],
                            ];

        $query = Unit::query()->orderBy('id', 'desc');

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
