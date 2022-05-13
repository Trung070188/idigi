<?php

namespace App\Http\Controllers\Admin;


use App\Models\UserDevice;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Lesson;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;


class LessonsController extends AdminBaseController
{
    public static $menus = [
        [
            'name' => 'Lesson',
            'icon' => 'fa fa-shopping-cart',
            'url' => '/xadmin/lessons/index',
        ]
    ];

    /**
     * Index page
     * @uri  /xadmin/lessons/index
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function index()
    {
        $title = 'Lesson';
        $component = 'LessonIndex';

        return component($component, compact('title'));
    }

    /**
     * Create new entry
     * @uri  /xadmin/lessons/create
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function create(Request $req)
    {
        $component = 'LessonForm';
        $title = 'Create lessons';
        return component($component, compact('title'));
    }

    /**
     * @uri  /xadmin/lessons/edit?id=$id
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function edit(Request $req)
    {
        $id = $req->id;
        $entry = Lesson::find($id);

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        /**
         * @var  Lesson $entry
         */

        $title = 'Edit';
        $component = 'LessonForm';


        return component($component, compact('title', 'entry'));
    }

    /**
     * @uri  /xadmin/lessons/remove
     * @return  array
     */
    public function remove(Request $req)
    {
        $id = $req->id;
        $entry = Lesson::find($id);

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
     * @uri  /xadmin/lessons/removeAll
     * @return  array
     */
    public function removeAll(Request $req)
    {
        $ids = $req->ids;
        Lesson::whereIn('id', $ids)->delete();


        return [
            'code' => 0,
            'message' => 'Đã xóa'
        ];
    }

    /**
     * @uri  /xadmin/lessons/save
     * @return  array
     */
    public function save(Request $req)
    {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');

        $rules = [
            'created_by' => 'max:255',
            'created_date' => 'date_format:Y-m-d H:i:s',
            'last_modified_by' => 'max:255',
            'last_modified_date' => 'date_format:Y-m-d H:i:s',
            'name' => 'max:255',
            'subject' => 'max:255',
            'number' => 'max:255',
        ];

        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }

        /**
         * @var  Lesson $entry
         */
        if (isset($data['id'])) {
            $entry = Lesson::find($data['id']);
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
        } else {
            $entry = new Lesson();
            $entry->fill($data);
            $entry->save();

            return [
                'code' => 0,
                'message' => 'Đã thêm',
                'id' => $entry->id
            ];
        }
    }

    /**
     * @param Request $req
     */
    public function toggleStatus(Request $req)
    {
        $id = $req->get('id');
        $entry = Lesson::find($id);

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
     * @uri  /xadmin/lessons/data
     * @return  array
     */
    public function data(Request $req)
    {
        $query = Lesson::query()->orderBy('id', 'desc');

        if ($req->keyword) {
            $query->where('name', 'LIKE', '%' . $req->keyword . '%');
        }
        if ($req->subject) {
            $query->where('subject', $req->subject);

        }

        if ($req->grade) {
            $query->where('grade', $req->grade);
        }

        if ($req->enabled) {
            $query->where('enabled', $req->enabled);
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
                'totalRecord' => $query->count(),
            ]
        ];
    }

    public function export()
    {
        $keys = [
            'created_by' => ['A', 'created_by'],
            'created_date' => ['B', 'created_date'],
            'enabled' => ['C', 'enabled'],
            'grade' => ['D', 'grade'],
            'last_modified_by' => ['E', 'last_modified_by'],
            'last_modified_date' => ['F', 'last_modified_date'],
            'name' => ['G', 'name'],
            'rating' => ['H', 'rating'],
            'shared' => ['I', 'shared'],
            'structure' => ['J', 'structure'],
            'subject' => ['K', 'subject'],
            'unit' => ['L', 'unit'],
            'number' => ['M', 'number'],
            'customized' => ['N', 'customized'],
        ];

        $query = Lesson::query()->orderBy('id', 'desc');

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

    public function downloadLesson(Request $request)
    {
        ob_get_clean();

        if ($request->isMethod('POST')) {
            throw new MethodNotAllowedException("405");
        }
        $y = date('Y');
        $m = date('m');
        $d = date('d');
        $dir = "files/downloads/{$y}/{$m}/{$d}";

        if (!is_dir(public_path($dir))) {
            mkdir(public_path($dir), 0755, true);
        }

        $lessons = Lesson::whereIn('id', explode(',', $request->lessonIds))
            ->with(['inventories'])->get();


        foreach ($lessons as $lesson) {
            $filename = uniqid(time());
            $zip_file = public_path($dir . '/lessons_'.$filename.'.zip');
            $zip = new \ZipArchive();
            $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            if ($lesson->inventories) {
                foreach ($lesson->inventories as $inventory) {
                    $icon = 'Icons/' . basename(public_path($inventory->image));
                    $link = basename(public_path($inventory->virtual_path));

                    $zip->addFile(public_path($inventory->image), $icon);
                    $zip->setEncryptionName($icon, \ZipArchive::EM_AES_256, env('SECRET_KEY'));

                    $zip->addFile(public_path($inventory->virtual_path), $link);
                    $zip->setEncryptionName($link, \ZipArchive::EM_AES_256, env('SECRET_KEY'));

                }
            }

            Storage::put($dir . '/lesson_detail'.$filename.'.txt', $lesson->structure);

            $zip->addFile(storage_path('app/' . $dir . '/lesson_detail'.$filename.'.txt'), 'lesson_detail.txt');
            $zip->setEncryptionName('lesson_detail.txt', \ZipArchive::EM_AES_256, env('SECRET_KEY'));
            $zip->close();

            return response()->download($zip_file);
        }
    }

}
