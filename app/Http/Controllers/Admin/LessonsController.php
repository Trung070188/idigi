<?php

namespace App\Http\Controllers\Admin;


use App\Console\Commands\DownloadLesson;
use App\Jobs\UpdateDownloadInventory;
use App\Jobs\UpdateDownloadLessonFile;
use App\Models\AllocationContent;
use App\Models\AllocationContentSchool;
use App\Models\DownloadAppLog;
use App\Models\DownloadLessonFile;
use App\Models\DownloadLessonLog;
use App\Models\Inventory;
use App\Models\LessonInventory;
use App\Models\School;
use App\Models\Unit;
use App\Models\UserDevice;
use Carbon\Carbon;
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
        $entry = Lesson::with(['inventories'])->where('id', $id)->first();

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        /**
         * @var  Lesson $entry
         */

        $title = 'Edit';
        $component = 'LessonDetail';


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
        $lesson = Lesson::whereIn('id', $req->lessonIds)->delete();
        if ($lesson){
            return [
                'code' => 0,
                'message' => 'Đã xóa'
            ];
        }else{
            return [
                'code' => 500,
                'message' => 'Lỗi'
            ];
        }
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
            'name' => ['required', 'max:100', "regex:/^[\p{L}\s\/0-9.,'?\(\)_:-]+$/u"],
            'subject' => 'required|max:200',
            'description' => 'max:200'
        ];

        $messages = [
            'name.regex'  => 'The :attribute field contains invalid characters, letters, numbers, space and _ - : , . ( ) ? \' are allowed.',
        ];

        $v = Validator::make($data, $rules, $messages);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }

        /**
         * @var  Lesson $entry
         */
        $message = 'Đã thêm';

        if (isset($data['id'])) {
            $entry = Lesson::find($data['id']);
            if (!$entry) {
                return [
                    'code' => 3,
                    'message' => 'Không tìm thấy',
                ];
            }

            $unit = Unit::query()->where('id', $data['unit_id'])->first();
            $data['unit_name'] = $unit['unit_name'];
            $entry->fill($data);
            $entry->save();

            LessonInventory::where('lesson_id', $entry->id)->delete();

            if($req->inventory){
                foreach ($req->inventory as $key => $inven) {
                    LessonInventory::create([
                        'lesson_id' => $entry->id,
                        'inventory_id' => $inven['id'],
                        'order' => $key + 1,
                    ]);
                }
            }


            $message = 'Đã cập nhật';


        } else {
            $entry = new Lesson();
            $unit = Unit::query()->where('id', $data['unit_id'])->first();
            $data['unit_name'] = $unit['unit_name'];
            $entry->fill($data);
            $entry->save();

            if($req->inventory){
                foreach ($req->inventory as $key => $inven) {
                    LessonInventory::create([
                        'lesson_id' => $entry->id,
                        'inventory_id' => $inven['id'],
                        'order' => $key + 1,
                    ]);
                }
            }

        }

        $lesson = Lesson::query()->with(['inventories', 'unit1', 'unit1.course'])->where('id', $entry->id)->first();

        $inventoryData = [];

        if($lesson->inventories){
            foreach ($lesson->inventories as $inventory) {
                $pathArr = explode('/', $inventory->virtual_path);
                $inventoryData[] = [
                    "idSublesson" => $inventory->id,
                    "pathIcon" => "",
                    "name" => $inventory->name,
                    "time" => "",
                    "type" => $inventory->type,
                    "link" => $pathArr[count($pathArr) - 1],
                    "full_link" => url('/api/download/inventory/' . $inventory->id)
                ];
            }
            $lessonNameArr = explode(':', $lesson->name);
            $structure = [
                "idSubject" => $entry->subject == 'Science' ? 1 : 0,
                "codeSubject" => $entry->subject,
                "nameSubject" => 'iSMART ' . $entry->subject,
                "grade" => @$lesson->unit1->course->grade,
                "idUnit" => $entry->position,
                "titleUnit" => $entry->unit_name,
                "nameUnit" => $entry->unit_name,
                "idLesson" => $lesson->id,
                "codeLesson" => $lessonNameArr[0],
                "titleLesson" => $lesson->name,
                "nameLesson" => $lesson->name,
                "subLessons" => $inventoryData,

            ];

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
        $entry = Lesson::find($id);

        if (!$id) {
            return [
                'code' => 404,
                'message' => 'Not Found'
            ];
        }

        $entry->enabled = $req->status ? 1 : 0;
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
        $countLesson = Lesson::query()->orderBy('id', 'desc')->count();
        $user = Auth::user();

        $schoolIds = explode(',', $user->school_id);
        $schoolIdArr = [];

        foreach ($schoolIds as $schoolId) {
            $schoolIdArr[] = (int)$schoolId;
        }
        $schools = School::whereIn('id', $schoolIdArr)->get();
        $unitIds = [];
        $isSuperAdmin = 0;
        $roleName = "";

        foreach ($user->roles as $role) {
            $roleName = $role->role_name;
            if ($role->role_name == 'Teacher') {
                if ($user->active_allocation == 1) {
                    if ($user->user_units) {
                        foreach ($user->user_units as $unit) {
                            $unitIds[] = $unit->unit_id;
                        }
                    }
                }
            }
            if ($req->schoolLesson !== 'null') {
                if ($role->role_name == 'School Admin') {
                    $school = School::where('id', $req->schoolLesson)->first();
                    if ($school->active_allocation == 1) {
                        $contents = AllocationContentSchool::where('school_id', $req->schoolLesson)
                            ->with(['allocation_content', 'allocation_content.units'])
                            ->get();
                        foreach ($contents as $content) {
                            if (@$content->allocation_content->units) {
                                foreach ($content->allocation_content->units as $unit) {
                                    $unitIds[] = $unit->id;
                                }

                            }
                        }
                    }
                }
            } else {
                if ($role->role_name == 'School Admin') {
                    $contents = AllocationContentSchool::WhereIn('school_id', $schoolIdArr)
                        ->with(['allocation_content', 'allocation_content.units'])
                        ->get();
                    foreach ($contents as $content) {
                        if (@$content->allocation_content->units) {
                            foreach ($content->allocation_content->units as $unit) {
                                $unitIds[] = $unit->id;
                            }

                        }
                    }
                }
            }

            if ($role->role_name != 'Teacher' && $role->role_name != 'School Admin') {
                $isSuperAdmin = 1;
            }

        }

        $query = Lesson::query()->with(['user_units', 'unit'])
            ->orderBy('id', 'ASC');

        if ($isSuperAdmin == 0) {
            $query = $query->whereIn('unit_id', $unitIds);
        }


        if ($req->keyword) {
            $query->where('name', 'LIKE', '%' . $req->keyword . '%');
        }
        if ($req->name) {
            $query->where('name', $req->name);
        }
        if ($req->subject) {
            $query->where('subject', $req->subject);

        }

        if ($req->unit) {
            $query->where('unit_id', $req->unit);
        }

        if ($req->enabled != '') {
            $query->where('enabled', $req->enabled);
        }

        $query->createdIn($req->created);

        $limit = 25;

        if ($req->limit) {
            $limit = $req->limit;
        }


        $entries = $query->paginate($limit);
        $items = $entries->items();
        $downloadLessons = DownloadLessonLog::where('lesson_ids', '<>', NULL)->get();

        foreach ($downloadLessons as $downloadLesson){
            $lessonIds = explode(',', $downloadLesson->lesson_ids);
            foreach ($lessonIds as $lessonId){
                foreach ($items as $item){
                    if($item->id == $lessonId){
                        if(isset($item->total_download)){
                            $item->total_download ++;
                        }else{
                            $item->total_download = 0;
                        }
                    }
                }
            }
        }

        return [
            'code' => 0,
            'user' => $user,
            'data' => $items,
            'schools' => $schools,
            'roleName' => $roleName,
            'countLesson' => $countLesson,
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
                'totalRecord' => $query->count(),
            ]
        ];
    }

    public function dataCreateLesson(Request $req)
    {

        if ($req->subject) {
            $modules = Inventory::query()->select([
                'inventories.id as id',
                'inventories.name as label',
                'inventories.type as type',
                'inventories.subject as subject'
            ])->where('subject', $req->subject)->limit(100)->orderBy('id', 'desc');
            $units = Unit::query()->where('subject', $req->subject)->orderBy('id', 'desc')->get();
        } else {
            $modules = Inventory::query()->select([
                'inventories.id as id',
                'inventories.name as label',
                'inventories.type as type',
                'inventories.subject as subject'
            ])->limit(100)->orderBy('id', 'desc');
            $units = Unit::query()->orderBy('id', 'desc')->get();

        }
        if ($req->type) {
            $modules->where('type', $req->type)->select([
                'inventories.id as id',
                'inventories.name as label',
                'inventories.type as type'
            ])->limit(100)->orderBy('id', 'desc');
        }
        return [
            'units' => $units,
            'module' => $modules->get(),
        ];
    }

    public function getModules(Request $req)
    {
        $modules = Inventory::query()->select([
            'inventories.id as id',
            'inventories.name as label',
            'inventories.type as type',
            'inventories.subject as subject'
        ])->whereDoesntHave('lessons')
            ->limit(100)->orderBy('id', 'desc');

        if ($req->subject) {
            $modules = $modules->where(function($q) use ($req){
                    $q->where('subject', $req->subject);
                    $q->orWhere('subject', NULL);
                });
        }
        if ($req->type) {
            $modules = $modules->where('type', $req->type);
        }
        if ($req->keyword) {
            $modules = $modules->where('name', 'LIKE', '%' . $req->keyword . '%');
        }

        return $modules->get();
    }

    public function dataEditLesson(Request $req)
    {
        $lessons = DB::table('lessons')->leftJoin('lesson_inventory', function ($join) {
            $join->on('lessons.id', '=', 'lesson_inventory.lesson_id');
        })->leftJoin('inventories', function ($join) {
            $join->on('inventories.id', '=', 'lesson_inventory.inventory_id');
        })->where('lessons.id', $req->id);
        $lessons = $lessons->select([
            'lessons.id as lesson_id',
            'inventories.id as inventory_id',
            'inventories.name as label',
            'inventories.type as type'
        ])->get();
        if ($req->subject) {
            $units = Unit::query()->where('subject', $req->subject)->orderBy('id', 'desc')->get();

            $modules = Inventory::query()->select([
                'inventories.id as id',
                'inventories.name as label',
                'inventories.type as type'
            ])->where('subject', $req->subject)->orderBy('id', 'desc');
        } else {
            $units = Unit::query()->orderBy('id', 'desc')->get();
            $modules = Inventory::query()->select([
                'inventories.id as id',
                'inventories.name as label',
                'inventories.type as type'
            ])->orderBy('id', 'desc');

        }

        $module = [];
        foreach ($lessons as $lesson) {
            $module[] = $lesson->inventory_id;
        }

        if ($req->type) {
            $modules->where('type', $req->type)->orWhereIn('id', $module)->select([
                'inventories.id as id',
                'inventories.name as label',
                'inventories.type as type'
            ])->orderBy('id', 'desc');
        }
        return [
            'units' => $units,
            'lessons' => $lessons,
            'module' => $modules->get(),
        ];
    }

    public function downloadLesson(Request $request)
    {
        ob_get_clean();

        /*if ($request->isMethod('POST')) {
            throw new MethodNotAllowedException("405");
        }*/

        $user = Auth::user();
        $userDevice = UserDevice::where('user_id', $user->id)->where('id', $request->device)->first();
        $password = env('SECRET_KEY') . '_' . @$userDevice->secret_key;

        $y = date('Y');
        $m = date('m');
        $d = date('d');
        $dir = "files/downloads/{$y}/{$m}/{$d}/{$user->id}";

        if (!is_dir(public_path($dir))) {
            mkdir(public_path($dir), 0755, true);
        }

        $lessons = Lesson::whereIn('id', $request->lessonIds)
            ->with(['inventories', 'unit1', 'unit1.course'])->get();


        $filenameAll = uniqid(time() . rand(10, 100));
        $pathZipAll = $dir . '/all_lessons_' . $filenameAll . '.zip';
        $zipFileAll = public_path($pathZipAll);
        $zipAll = new \ZipArchive();
        $zipAll->open($zipFileAll, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $lessonLog = DownloadLessonLog::create([
            'user_id' => $user->id,
            'ip_address' => $request->getClientIp(),
            'user_agent' => $request->userAgent(),
            'device_uid' => @$userDevice->device_uid,
            'lesson_ids' => implode(',', $request->lessonIds),
            'download_at' => Carbon::now(),
        ]);

        foreach ($lessons as $key => $lesson) {
            $filename = uniqid(time() . rand(10, 100));
            $name = explode(':', $lesson->name);
            $zip_file = public_path($dir . '/' . $name[0] . '.zip');
            $zip = new \ZipArchive();
            $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            if ($lesson->inventories) {
                foreach ($lesson->inventories as $inventory) {
                    $icon = 'Icons/' . basename(public_path($inventory->image));
                    $link = basename(public_path($inventory->virtual_path));

                    if (file_exists(public_path($inventory->image)) && is_file(public_path($inventory->image))) {
                        $zip->addFile(public_path($inventory->image), $icon);
                        $zip->setEncryptionName($icon, \ZipArchive::EM_AES_256, $password);
                    }
                    if (file_exists(public_path($inventory->virtual_path)) && is_file(public_path($inventory->virtual_path))) {
                        $zip->addFile(public_path($inventory->virtual_path), $link);
                        $zip->setEncryptionName($link, \ZipArchive::EM_AES_256, $password);
                    }

                    $dataDownloadInventory = [
                        'user_id' => $user->id,
                        'ip_address' => $request->getClientIp(),
                        'user_agent' => $request->userAgent(),
                        'device_uid' => @$userDevice->device_uid,
                        'lesson_id' => $lesson->id,
                        'download_at' => Carbon::now(),
                        'type' => 'cms',
                        'inventory_id' => $inventory->id
                    ];
                    $this->dispatch(new UpdateDownloadInventory($dataDownloadInventory));

                }
            }
            $inventoryData = [];
            $lessonNameArr = explode(':', $lesson->name);
            $structure = [
                "idSubject" => @$lesson->unit1->course->subject == 'Science' ? 1 : 0,
                "codeSubject" => @$lesson->unit1->course->subject,
                "nameSubject" => 'iSMART ' .@$lesson->unit1->course->subject,
                "grade" => @$lesson->unit1->course->grade,
                "idUnit" => @$lesson->unit1->position,
                "titleUnit" => @$lesson->unit1->unit_name,
                "nameUnit" => @$lesson->unit1->unit_name,
                "idLesson" => $lesson->id,
                "codeLesson" => $lessonNameArr[0],
                "titleLesson" => count($lessonNameArr) > 1 ? trim($lessonNameArr[1]) :$lessonNameArr[0],
                "nameLesson" => $lesson->name,
                "position" => $lesson->position ? $lesson->position : 0,
                "subLessons" => [],


            ];

            if($lesson->inventories){
                foreach ($lesson->inventories as $inventory) {
                    $pathArr = explode('/', $inventory->virtual_path);
                    $inventoryName =  explode('_', $inventory->name);
                    $inventoryData[] = [
                        "idSublesson" => $inventory->id,
                        "pathIcon" => "",
                        "name" => count($inventoryName) >  1 ? $inventoryName[1] :$inventoryName[0],
                        "time" => "",
                        "type" => $inventory->type,
                        "link" => $pathArr[count($pathArr) - 1],
                        "full_link" => url('/api/download/inventory/' . $inventory->id)
                    ];
                }

                $structure["subLessons"] = $inventoryData;

            }

            Storage::put($dir . '/lesson_detail' . $filename . '.txt', json_encode($structure));

            $zip->addFile(storage_path('app/' . $dir . '/lesson_detail' . $filename . '.txt'), 'lesson_detail.txt');
            $zip->setEncryptionName('lesson_detail.txt', \ZipArchive::EM_AES_256, $password);
            $zip->close();

            $dataLessonFile = [
                'download_lesson_log_id' => $lessonLog->id,
                'path' => $zip_file,
                'is_main' => 0,
                'is_deleted_file' => 0,
                'source' => 'lesson',
            ];
            $this->dispatch(new UpdateDownloadLessonFile($dataLessonFile));

            $zipAll->addFile($zip_file, $name[0] . '.zip');
            $zipAll->setEncryptionName('/' . $name[0] . '.zip', \ZipArchive::EM_AES_256, $password);

        }

        $zipAll->close();

        $dataLessonFile = [
            'download_lesson_log_id' => $lessonLog->id,
            'path' => $zipFileAll,
            'is_main' => 1,
            'is_deleted_file' => 0,
            'source' => 'lesson',
        ];
        $this->dispatch(new UpdateDownloadLessonFile($dataLessonFile));

        return [
            'code' => 0,
            'url' => url($pathZipAll)
        ];

    }

    public function getLessons(Request $req)
    {
        $lessons = Lesson::query()->select(['id', 'name as label', 'subject', 'unit_id'])->orderBy('name', 'ASC')->get();
        return $lessons;
    }

}
