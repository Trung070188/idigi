<?php

namespace App\Http\Controllers\Admin;


use App\Helpers\PermissionField;
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
        return component($component, compact('title'));
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
        if(@$entry->fileImage){
            $entry->file_image_new= [
                'id' => @$entry->fileImage->id,
                'uri' => @$entry->fileImage->url,
                'is_image' => 1,
            ];
        }else{
            $entry->file_image_new = NULL;
        }
        if(@$entry->fileAsset){
            $entry->file_asset_new =[
                'id' => @$entry->fileAsset->id,
                'uri' => @$entry->fileAsset->url,
                'extension' => @$entry->fileAsset->extension,
                'name' => @$entry->fileAsset->name,
                'is_image' => 0,
            ];
        }else{
            $entry->file_asset_new = NULL;
        }

        /**
         * @var  Inventory $entry
         */
        $user = Auth::user();
        $permissionDetail = new PermissionField();
        $permissions = $permissionDetail->permission($user);
        $permissionFields = [
            'resource_name' => $permissionDetail->havePermission('resource_name',$permissions,$user),
            'resource_type'=>$permissionDetail->havePermission('resource_type',$permissions,$user),
            'resource_subject'=>$permissionDetail->havePermission('resource_subject',$permissions,$user),
            'resource_grade'=>$permissionDetail->havePermission('resource_grade',$permissions,$user),
            'resource_picture'=>$permissionDetail->havePermission('resource_picture',$permissions,$user),
            'resource_file_asset_bundle'=>$permissionDetail->havePermission('resource_file_asset_bundle',$permissions,$user),
            'resource_description'=>$permissionDetail->havePermission('resource_description',$permissions,$user),
            'resource_tags'=>$permissionDetail->havePermission('resource_tags',$permissions,$user),
            'resource_active'=>$permissionDetail->havePermission('resource_active',$permissions,$user)

        ];

        $title = 'Edit';
        $component = 'InventoryForm';
        $jsonData = [
            'permissionFields'=>$permissionFields,
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
            'name' => 'max:255|required',
            'file_asset_new' => 'required',
            'subject' => 'max:255|required',
            'type' => 'max:255|required',
            'grade' => 'max:255|required',
            'link_webview' => 'max:255',
            'tags' => 'max:1000',
        ];

        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }

        $data['file_image_id'] = @$data['file_image_new']['id'];
        $data['file_asset_id'] = @$data['file_asset_new']['id'];

        $data['virtual_path'] = str_replace('APP_URL', '',  $data['file_asset_new']['uri']);
        $data['physical_path'] = public_path($data['virtual_path']);

        if($data['file_image_id']){
            $data['image'] = str_replace('APP_URL', '',  $data['file_image_new']['uri']);
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

        $entry->status = $req->status ? 1 : 0;
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
        $countInventory=Inventory::query()->orderBy('id','desc')->count();
        $query = Inventory::query();
        if ($req->order && $req->sortBy) {
            $query = $query->orderBy($req->order, $req->sortBy);
        } else {
            $query = $query->orderBy('id', 'desc');
        }


        if ($req->keyword) {
            $query->where('name', 'LIKE', '%' . $req->keyword. '%');
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

        if ($req->enabled!='') {
            $query->where('enabled',  $req->enabled);
        }


        $query->createdIn($req->created);
        $limit =25;

        if ($req->limit) {
            $limit = $req->limit;
        }
        $entries = $query->paginate($limit);


        return [
            'countInventory'=>$countInventory,
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
