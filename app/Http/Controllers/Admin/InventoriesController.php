<?php

namespace App\Http\Controllers\Admin;


use App\Helpers\PermissionField;
use App\Models\Lesson;
use App\Models\LessonInventory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Inventory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class InventoriesController extends AdminBaseController
{
    public static $menus = [
        [
            'name' => 'Inventory',
            'icon' => 'fa fa-shopping-cart',
            'url' => '/xadmin/inventories/index',
        ]
    ];

    /**
     * Index page
     * @uri  /xadmin/inventories/index
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function __construct()
    {
    }
    public function index()
    {
        $title = 'Inventory';
        $component = 'InventoryIndex';
        return component($component, compact('title'));
    }

    /**
     * Create new entry
     * @uri  /xadmin/inventories/create
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function create(Request $req)
    {
        $component = 'InventoryForm';
        $title = 'Create inventories';
        $permissionList = [
            'module_name',
            'module_type',
            'module_file_asset_bundle',
            'module_description',
            'module_location',
            'module_subject',
            'module_lesson',
            'module_active',
            'module_delete',
        ];

        $user = Auth::user();
        $permissionDetail = new PermissionField();
        $permissions = $permissionDetail->permission($user);
        $permissionFields = [];
        foreach ($permissionList as $permission) {
            $haspermission = $permissionDetail->havePermission($permission, $permissions, $user);
            $permissionFields[(string)$permission] = (bool)$haspermission;
        }
        $jsonData = [
            'permissionFields' => $permissionFields,
        ];

        return component($component, compact('title','component','jsonData'));
    }

    /**
     * @uri  /xadmin/inventories/edit?id=$id
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function edit(Request $req)
    {
        $id = $req->id;
        $entry = Inventory::find($id);

        if (!$entry) {
            throw new NotFoundHttpException();
        }
        if (@$entry->fileImage) {
            $entry->file_image_new = [
                'id' => @$entry->fileImage->id,
                'uri' => @$entry->fileImage->url,
                'is_image' => 1,
            ];
        } else {
            $entry->file_image_new = NULL;
        }

        $entry->lessonId = NULL;
        if ($entry->lessons) {
            foreach ($entry->lessons as $lesson) {
                $entry->lessonId = $lesson->id;
            }
        }
        if (@$entry->fileAsset) {
            $entry->file_asset_new = [
                'id' => @$entry->fileAsset->id,
                'uri' => @$entry->fileAsset->url,
                'extension' => @$entry->fileAsset->extension,
                'name' => @$entry->fileAsset->name,
                'is_image' => 0,
            ];
        } else {
            $entry->file_asset_new = NULL;
        }

        /**
         * @var  Inventory $entry
         */
        $user = Auth::user();
        $permissionDetail = new PermissionField();
        $permissions = $permissionDetail->permission($user);
        $permissionFields = [];
        $permissionList = [
            'module_name',
            'module_type',
            'module_file_asset_bundle',
            'module_description',
            'module_location',
            'module_subject',
            'module_lesson',
            'module_active',
            'module_delete',
        ];

        foreach ($permissionList as $permission) {
            $haspermission = $permissionDetail->havePermission($permission, $permissions, $user);
            $permissionFields[(string)$permission] = (bool)$haspermission;
        }

        $title = 'Edit';
        $component = 'InventoryForm';
        $jsonData = [
            'permissionFields' => $permissionFields,
            'entry' => $entry,
        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }

    /**
     * @uri  /xadmin/inventories/remove
     * @return  array
     */
    public function remove(Request $req)
    {
        $id = $req->id;
        $entry = Inventory::find($id);

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
     * @uri  /xadmin/inventories/save
     * @return  array
     */
    public function save(Request $req)
    {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');


        $rules = [
            //'file_image_new' => 'required',
            'name' => ['required', 'max:100', 'regex:/^[\p{L}\s\/0-9.,?\(\)_:-]+$/u'],
            'file_asset_new' => 'required',
            //            'subject' => 'max:255|required',
            'type' => 'max:255|required',
            //            'grade' => 'max:255|required',
            'link_webview' => 'max:255',
            'tags' => 'max:1000',
        ];
        if ($data['location'] == 1) {
            $rules['lessonId'] = ['required'];
        }


        $v = Validator::make($data, $rules, ['lessonId.required' => 'The lesson field is required.', 'file_asset_new.required' => 'The file asset bundle is required.']);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }

        $data['file_image_id'] = @$data['file_image_new']['id'];
        $data['file_asset_id'] = @$data['file_asset_new']['id'];

        $data['virtual_path'] = str_replace(env('APP_URL'), '',  $data['file_asset_new']['uri']);
        $data['physical_path'] = public_path($data['virtual_path']);

        if ($data['file_image_id']) {
            $data['image'] = str_replace(env('APP_URL'), '',  $data['file_image_new']['uri']);
        }


        /**
         * @var  Inventory $entry
         */
        if (isset($data['id'])) {
            $entry = Inventory::find($data['id']);
            if (!$entry) {
                return [
                    'code' => 3,
                    'message' => 'Không tìm thấy',
                ];
            }

            $entry->fill($data);
            if ($data['location'] == 1) {
                LessonInventory::query()->where('inventory_id', $data['id'])->delete();
                LessonInventory::query()->create([
                    'lesson_id' => $data['lessonId'],
                    'inventory_id' => $data['id']
                ]);
            } else {
                LessonInventory::query()->where('inventory_id', $data['id'])->delete();
            }


            $entry->save();
            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
                'id' => $entry->id
            ];
        } else {
            $entry = new Inventory();
            $entry->fill($data);
            $entry->save();
            if ($entry->location == 1) {
                LessonInventory::query()->create([
                    'inventory_id' => $entry->id,
                    'lesson_id' => $data['lessonId'],
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
     * @param Request $req
     */
    public function toggleStatus(Request $req)
    {
        $id = $req->get('id');
        $entry = Inventory::find($id);

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
     * @uri  /xadmin/inventories/data
     * @return  array
     */
    public function data(Request $req)
    {
        $countInventory = Inventory::query()->orderBy('id', 'desc')->count();
        $query = Inventory::query()->withCount(['download_inventory_logs']);
        if ($req->order && $req->sortBy) {
            $query = $query->orderBy($req->order, $req->sortBy);
        } else {
            $query = $query->orderBy('id', 'desc');
        }


        if ($req->keyword) {
            $query->where('name', 'LIKE', '%' . $req->keyword . '%');
        }
        if ($req->subject) {
            $query->where('subject',  $req->subject);
        }
        if ($req->type) {
            $query->where('type',  $req->type);
        }
        if ($req->grade) {
            $query->where('grade',  $req->grade);
        }

        if ($req->enabled != '') {
            $query->where('enabled',  $req->enabled);
        }

        $query->createdIn($req->created);
        $count = $query->count();
        $limit = 25;

        if ($req->limit) {
            $limit = $req->limit;
        }

        $entries = $query->paginate($limit);
        $items = $entries->items();
        foreach ($items as $item) {
            $lesson_name = [];
            $lessons = Lesson::query()->whereHas('inventories', function ($q) use ($item) {
                $q->where('inventory_id', '=', $item->id);
            })->get();
            foreach ($lessons as $lesson) {
                $lesson_name[] = $lesson->name;
            }
            $item->lessons = implode(', ',$lesson_name);
        }


        return [
            'countInventory' => $countInventory,
            'code' => 0,
            'data' => $items,
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
                'totalRecord' => $count,
            ]
        ];
    }
    public function dataForm(Request $req)
    {
        $lessons = Lesson::query()->orderBy('name', 'ASC');
        if ($req->subject) {
            $lessons->where('subject', $req->subject);
        }
        if ($req->id) {
            $lesson = Lesson::query()->whereHas('inventories', function ($q) use ($req) {
                $q->where('inventory_id', '=', $req->id);
            })->get();
        }
        return [
            'lessons' => $lessons->get(),
            'lesson' => @$lesson

        ];
    }

    public function export()
    {
        $keys = [
            'description' => ['A', 'description'],
            'enabled' => ['B', 'enabled'],
            'grade' => ['C', 'grade'],
            'image' => ['D', 'image'],
            'name' => ['E', 'name'],
            'physical_path' => ['F', 'physical_path'],
            'rating' => ['G', 'rating'],
            'subject' => ['H', 'subject'],
            'type' => ['I', 'type'],
            'virtual_path' => ['J', 'virtual_path'],
            'slideshows' => ['K', 'slideshows'],
            'link_webview' => ['L', 'link_webview'],
            'duration' => ['M', 'duration'],
            'tags' => ['N', 'tags'],
            'created_by' => ['O', 'created_by'],
            'updated_by' => ['P', 'updated_by'],
            'deleted_by' => ['Q', 'deleted_by'],
        ];

        $query = Inventory::query()->orderBy('id', 'desc');

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
        Inventory::whereIn('id', $ids)->delete();
        return [
            'code' => 0,
            'message' => 'Đã xóa'
        ];
    }
}
