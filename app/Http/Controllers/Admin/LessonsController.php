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
        $entry = Lesson::find($id);

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
            'name' => 'required|max:255',
            'subject' => 'required|max:255',
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
            if($entry->unit_id)
            {
                Lesson::query()->where('unit_id',$data['unit_id'])->update(['unit_id'=>NULL,'unit_name'=>NUll]);
            }
            $unit=Unit::query()->where('id',$data['unit_id'])->first();
            $entry->fill($data);
            $entry->unit_name=$unit['unit_name'];
            if($req->inventory)
            {
                LessonInventory::where('lesson_id',$entry->id)->delete();
            }
            foreach ($req->inventory as $inven)
            {
                 LessonInventory::create(['lesson_id'=>$entry->id,'inventory_id'=>$inven['id']]);
            }
            $entry->save();

            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
                'id' => $entry->id
            ];
        } else {
            $entry = new Lesson();
            $entry->fill($data);
            Lesson::query()->where('unit_id',$data['unit_id'])->update(['unit_id'=>NULL,'unit_name'=>NUll]);
            $unit=Unit::query()->where('id',$data['unit_id'])->first();
            $entry->unit_name=@$unit['unit_name'];
            $entry->save();
            foreach ($req->inventory as $inven)
            {
                LessonInventory::create(['lesson_id'=>$entry->id,'inventory_id'=>$inven['id']]);
            }
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
        $countLesson=Lesson::query()->orderBy('id','desc')->count();
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
                $roleName=$role->role_name;
                if ($role->role_name == 'Teacher') {
                    if($user->active_allocation==1)
                    {
                        if ($user->user_units) {
                            foreach ($user->user_units as $unit) {
                                $unitIds[] = $unit->unit_id;
                            }
                        }
                    }
                }
                if($req->schoolLesson!=='null')
                {
                    if ($role->role_name == 'School Admin') {
                        $school=School::where('id',$req->schoolLesson)->first();
                        if($school->active_allocation==1)
                        {
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
                }
                else{
                    if ($role->role_name == 'School Admin') {
                        $contents = AllocationContentSchool::WhereIn('school_id',$schoolIdArr)
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

                if ($role->role_name == 'Super Administrator') {
                    $isSuperAdmin = 1;
                }

            }

        $query = Lesson::query()->with(['user_units'])
            ->orderBy('id', 'ASC');

        if($isSuperAdmin == 0){
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

        if ($req->grade) {
            $query->where('grade', $req->grade);
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

        return [
            'code' => 0,
            'user'=>$user,
            'data' => $entries->items(),
            'schools'=>$schools,
            'roleName'=>$roleName,
            'countLesson'=>$countLesson,
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
                'totalRecord' => $query->count(),
            ]
        ];
    }
    public function dataCreateLesson(Request $req)
    {


//        $modules=Inventory::query()->orderBy('id','desc');
        $modules=Inventory::query()->select([
            'inventories.id as id',
            'inventories.name as label',
            'inventories.type as type'
        ])->orderBy('id','desc');
        $units=Unit::query()->orderBy('id','desc')->get();

        if($req->type)
        {
            $modules->where('type',$req->type)->select([
                'inventories.id as id',
                'inventories.name as label',
                'inventories.type as type'
            ])->orderBy('id','desc');
        }
        return [
            'units'=>$units,
            'module'=>$modules->get(),
        ];
    }
    public function dataEditLesson(Request $req)
    {
        $lessons=DB::table('lessons')->leftJoin('lesson_inventory',function ($join)
        {
           $join->on('lessons.id','=','lesson_inventory.lesson_id');
        })->leftJoin('inventories',function ($join)
        {
            $join->on('inventories.id','=','lesson_inventory.inventory_id');
        })->where('lessons.id',$req->id);
        $units=Unit::query()->orderBy('id','desc')->get();
      $lessons= $lessons->select([
            'lessons.id as lesson_id',
            'inventories.id as inventory_id',
            'inventories.name as name',
            'inventories.type as type'
       ])->get();
//    $modules=DB::table('inventories')->select([
//            'inventories.id as id',
//           'inventories.name as label',
//           'inventories.type as type'
//    ])->orderBy('id','desc');
        $modules=Inventory::query()->select([
           'inventories.id as id',
           'inventories.name as label',
           'inventories.type as type'
        ])->orderBy('id','desc');
        $module=[];
        foreach ($lessons as $lesson)
        {
            $module[]=$lesson->inventory_id;
        }

        if($req->type)
        {
            $modules->where('type',$req->type)->orWhereIn('id',$module)->select([
                'inventories.id as id',
                'inventories.name as label',
                'inventories.type as type'
            ])->orderBy('id','desc');
        }
        return [
            'units'=>$units,
            'lessons'=>$lessons,
            'module'=>$modules->get(),
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
            ->with(['inventories'])->get();


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
            $structure = json_decode($lesson->structure, true);


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

                   $dataDownloadInventory =  [
                        'user_id' => $user->id,
                        'ip_address' => $request->getClientIp(),
                        'user_agent' => $request->userAgent(),
                        'device_uid' =>  @$userDevice->device_uid,
                        'lesson_id' => $lesson->id,
                        'download_at' => Carbon::now(),
                        'type' => 'cms',
                        'inventory_id' => $inventory->id
                    ];
                    $this->dispatch(new UpdateDownloadInventory($dataDownloadInventory));

                }
            }

            Storage::put($dir . '/lesson_detail' . $filename . '.txt', json_encode($structure));

            $zip->addFile(storage_path('app/' . $dir . '/lesson_detail' . $filename . '.txt'), 'lesson_detail.txt');
            $zip->setEncryptionName('lesson_detail.txt', \ZipArchive::EM_AES_256, $password);
            $zip->close();

            $dataLessonFile = [
            'download_lesson_log_id' => $lessonLog->id,
                'path' => $zip_file,
                'is_main' => 0,
                'is_deleted_file' => 0
            ];
            $this->dispatch(new UpdateDownloadLessonFile($dataLessonFile));

            $zipAll->addFile($zip_file, $name[0] . '.zip');
            $zipAll->setEncryptionName('/' . $name[0] . '.zip', \ZipArchive::EM_AES_256, $password);

        }

        $zipAll->close();

        $dataLessonFile =[
            'download_lesson_log_id' => $lessonLog->id,
            'path' => $zipFileAll,
            'is_main' => 1,
            'is_deleted_file' => 0
        ];
        $this->dispatch(new UpdateDownloadLessonFile($dataLessonFile));

        return [
            'code' => 0,
            'url' => url($pathZipAll)
        ];

    }

}
