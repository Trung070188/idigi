<?php

namespace App\Http\Controllers\Admin;


use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonInventory;
use Carbon\Carbon;
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
    public function index()
    {
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
    public function create(Request $req)
    {
        $component = 'UnitForm';
        $title = 'Create units';
        return component($component, compact('title'));
    }

    /**
     * @uri  /xadmin/units/edit?id=$id
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function edit(Request $req)
    {
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
    public function remove(Request $req)
    {
        $id = $req->id;
        $entry = Unit::query()->where('id', $id)->update(['deleted_at' => Carbon::now()]);
        if (!$entry) {
            throw new NotFoundHttpException();
        }
        return [
            'code' => 0,
            'message' => 'Đã xóa'
        ];
    }

    function removeUnit(Request $req)
    {
        Unit::query()->whereIn('id', $req->unitIds)->update(['deleted_at' => Carbon::now()]);
        return [
            'code' => 0,
            'message' => 'Đã xóa'
        ];
    }

    /**
     * @uri  /xadmin/units/save
     * @return  array
     */
    public function save(Request $req)
    {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');

        $rules = [
            'unit_name' => ['required', 'max:100', 'regex:/^[\p{L}\s\/0-9.,?\(\)_-]+$/u'],
            'subject' => 'required',
            'description' => 'max:200'
        ];
        $message = [
            'unit_name.required' => 'The unit name field is required'
        ];

        $v = Validator::make($data, $rules, $message);

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
            if ($entry->course_id != $data['course_id']) {
                $dsUnit = Unit::query()->where('course_id', $data['course_id'])->count();
                $entry->fill($data);
                $entry->position = ($dsUnit + 1);
            } else {
                $entry->fill($data);
            }

            $entry->save();
            $message = 'Đã cập nhật';
        } else {
            $entry = new Unit();
            $entry->fill($data);
            $dsUnit = Unit::query()->where('course_id', $entry->course_id)->count();
            $entry->position = ($dsUnit + 1);
            $entry->save();
            $message = 'Đã thêm';


        }
        $lessonIds = [];
        foreach ($req->list as $_lesson){
            $lessonIds[] = $_lesson['id'];
        }
        $course = Course::where('id', $entry->course_id)>first();
        $lessons = Lesson::whereIn('id', $lessonIds)->with(['inventories'])->get();
        foreach ($lessons as $lesson) {

            $lessonNameArr = explode(':', $lesson->name);
            $inventories = $lesson->inventories;
            $inventoryData = [];
            foreach ($inventories as $inventory) {
                $pathArr = explode('/', $inventory->virtual_path);
                $inventoryData[] = [
                    "idSublesson" => $inventory->id,
                    "pathIcon" => "",
                    "name" => $inventory->name,
                    "time" => "",
                    "type" => $inventory->type,
                    "link" => $pathArr[count($pathArr) - 1],
                    "full_link" => asset($inventory->virtual_path)
                ];
            }
            $structure = [
                "idSubject" => $entry->subject == 'Science' ? 1 : 0,
                "codeSubject" => $entry->subject,
                "nameSubject" => 'iSMART ' . $entry->subject,
                "grade" => $course->grade,
                "idUnit" => $entry->position,
                "titleUnit" => $entry->unit_name,
                "nameUnit" => $entry->unit_name,
                "idLesson" => $lesson->id,
                "codeLesson" => $lessonNameArr[0],
                "titleLesson" => $lesson->name,
                "nameLesson" => $lesson->name,
                "subLessons" => $inventoryData,
            ];
            $lesson->unit_name = $entry->unit_name;
            $lesson->course_id = $entry->course_id;
            $lesson->unit_id = $entry->id;
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
        if ($req->course_id) {
            $query->where('course_id', 'LIKE', '%' . $req->course_id . '%');
        }

        $query->createdIn($req->created);
        $limit = 25;
        if ($req->limit) {
            $limit = $req->limit;
        }
        $entries = $query->paginate($limit);

        return [
            'code' => 0,
            'courses' => $courses,
            'count' => $query->count(),
            'data' => $entries->items(),
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
            ]
        ];
    }

    public function dataCreateUnit(Request $req)
    {
        if ($req->subject) {
            $lessons = Lesson::query()->where('subject', $req->subject)->orderBy('id', 'desc');
            $courses = Course::query()->where('subject', $req->subject)->orderBy('id', 'desc')->get();


        } else {
            $lessons = Lesson::query()->orderBy('id', 'desc');
            $courses = Course::query()->orderBy('id', 'desc')->get();

        }

        return [
            'lessons' => $lessons->get(),
            'courses' => $courses
        ];
    }

    public function dataEditUnit(Request $req)
    {
        $listLessons = Lesson::query()->where('unit_id', $req->id)->get();
        if ($req->subject) {
            $lessons = Lesson::query()->where('subject', $req->subject)->orderBy('id', 'desc');
            $courses = Course::query()->where('subject', $req->subject)->orderBy('id', 'desc')->get();


        } else {
            $lessons = Lesson::query()->orderBy('id', 'desc');
            $courses = Course::query()->orderBy('id', 'desc')->get();
        }

        return [
            'lessons' => $lessons->get(),
            'list_lessons' => $listLessons,
            'courses' => $courses

        ];
    }

    public function export()
    {
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
