<?php

namespace App\Http\Controllers\Admin;


use App\Models\Notification;
use App\Models\User;
use App\Models\UserRole;
use App\Notifications\InvoicePaid;
use App\Notifications\RequestRoleNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\RequestRole;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class RequestRolesController extends AdminBaseController
{
    public static $menus = [
        [
            'name' => 'RequestRole',
            'icon' => 'fa fa-shopping-cart',
            'url' => '/xadmin/request_role/index',
        ]
    ];

    /**
     * Index page
     * @uri  /xadmin/request_role/index
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->roles->count() > 0) {
            return redirect('/xadmin/lessons/index');
        }
        $title = 'RequestRole';
        $component = 'Request_roleIndex';
        return component($component, compact('title'));
    }

    /**
     * Create new entry
     * @uri  /xadmin/request_role/create
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function create(Request $req)
    {
        $component = 'Request_roleForm';
        $title = 'Create request_role';
        return component($component, compact('title'));
    }

    /**
     * @uri  /xadmin/request_role/edit?id=$id
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function edit(Request $req)
    {
        $id=$req->id;
        $entry = RequestRole::find($id);
        if (!$entry) {
            throw new NotFoundHttpException();
        }

        /**
         * @var  RequestRole $entry
         */

        $title = 'Edit';
        $component = 'Request_roleForm';
        return component($component, compact('title', 'entry'));
    }

    /**
     * @uri  /xadmin/request_role/remove
     * @return  array
     */
    public function remove(Request $req)
    {
        $id = $req->id;
        $entry = RequestRole::find($id);

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
     * @uri  /xadmin/request_role/save
     * @return  array
     */
    public function save(Request $req)
    {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->all();

        $rules = [
            'role' => 'required'
        ];

        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }

        $user = Auth::user();
        $data['user_id'] = $user->id;

        if ($data['role'] == 2) {
            $data['content'] = 'Tôi là quản trị viên';
            $data['role_name']='Moderator';
        }
        if($data['role']==1) {
            $data['content'] = 'Tôi là giáo viên';
            $data['role_name']='Teacher';
        }
        $data['status'] = 'Waiting';
        $users = User::with('roles')->orderBy('username')->get();



        RequestRole::updateOrCreate([
            'content' => $data['content'],
            'role_name'=>$data['role_name'],
            'status' => 'Waiting',
            'user_id' => $data['user_id'],

        ], $data);

        $data_device = new Notification();
        $data_device->status='new';
        if ($data['role'] == 2) {
            $data_device->content=  'Tôi là quản trị viên';
        }
        if($data['role']==1) {
            $data_device->content=  'Tôi là giáo viên';
        }
        $data_device->channel='inapp';
        $data_device->user_id=$user->id;
//        $data_device->url=url("/xadmin/request_roles/edit?user_id=$user->id");
        $data_device->title='Yêu cầu cấp quyền';
        $data_device->save();

        return [
            'code' => 0,
            'message' => 'Đã gửi yêu cầu cấp quyền thành công',
        ];
    }
    public function refuse(Request $req)
    {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');

        $rules = [
            'reason' => 'required|max:100',
//    'role_description' => 'max:255',
        ];

        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }

        /**
         * @var  RequestRole $entry
         */
        if (isset($data['id'])) {
            $entry = RequestRole::find($data['id']);
            if (!$entry) {
                return [
                    'code' => 3,
                    'message' => 'Không tìm thấy',
                ];
            }

            $entry->fill($data);
            $entry->status = 'Refuse';
            $entry->save();


            return [
                'code' => 0,
                'message' => 'Đã cập nhật',
                'id' => $entry->id
            ];
        } else {
            $entry = new RequestRole();
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
        $entry = RequestRole::find($id);

        if (!$id) {
            return [
                'code' => 404,
                'message' => 'Not Found'
            ];
        }

        $entry->status = $req->status ? 'Aprrove' : 'Waiting';
        $entry->save();

        return [
            'code' => 200,
            'message' => 'Đã lưu'
        ];
    }

    /**
     * Ajax data for index page
     * @uri  /xadmin/request_role/data
     * @return  array
     */
    public function data(Request $req)
    {
        $query = RequestRole::query()
            ->orderBy('id', 'desc');
        $users = User::with(['request_roles'])->orderBy('username', 'ASC')->get();

        if ($req->keyword) {
//            $query->where('title', 'LIKE', '%' . $req->keyword. '%');
        }

        $query->createdIn($req->created);

        $entries = $query->paginate();
        $data = [];

        foreach ($entries as $entry) {

            $data[] = [
                'id' => $entry->id,
                'content' => $entry->content,
                'status' => $entry->status,
                'created_at' => $entry->created_at,
                'user_id' => $entry->user_id,
                'reason' => $entry->reason,
                'users' => $users
            ];
        }


        return [
            'code' => 0,

            'data' => [
                'entries' => $data,
            ],
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
            ]
        ];
    }

    public function export()
    {
        $keys = [
            'user_id' => ['A', 'user_id'],
            'role_id' => ['B', 'role_id'],
            'status' => ['C', 'status'],
            'reason' => ['D', 'reason'],
            'content' => ['E', 'content'],
        ];

        $query = RequestRole::query()->orderBy('id', 'desc');

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
