<?php

namespace App\Http\Controllers\Admin;


use App\Models\Plan;
use App\Models\User;
use App\Models\UserDevice;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Notification;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class NotificationsController extends AdminBaseController
{
    public static $menus = [
        [
            'name' => 'Notification',
            'icon' => 'fa fa-shopping-cart',
            'url' => '/xadmin/notifications/index',
        ]
    ];

    /**
     * Index page
     * @uri  /xadmin/notifications/index
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function index()
    {
//        $user = Auth::user();
//        if($user->role !=  ){
//            return abort(403);
//        }
        $title = 'Notification';
        $component = 'NotificationIndex';
        return component($component, compact('title'));
    }

    /**
     * Create new entry
     * @uri  /xadmin/notifications/create
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function create(Request $req)
    {
        $component = 'NotificationForm';
        $title = 'Create notifications';
        return component($component, compact('title'));
    }

    /**
     * @uri  /xadmin/notifications/edit?id=$id
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function show(Request $req)
    {
        $id = $req->id;
        $user = Auth::user();
        $entry = Notification::find($id);

        $notification = Auth::user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
        }


        if (!$entry) {
            throw new NotFoundHttpException();
        }


        /**
         * @var  Notification $entry
         */

        $title = 'Edit';
        $component = 'NotificationForm';


        return component($component, compact('title', 'entry'));
    }

    /**
     * @uri  /xadmin/notifications/remove
     * @return  array
     */
    public function remove(Request $req)
    {
        $id = $req->id;
        $entry = Notification::find($id);

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
     * @uri  /xadmin/notifications/save
     * @return  array
     */
    public function save(Request $req)
    {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');

        $rules = [
            'type' => 'max:191',
            'url' => 'max:191',
            'sent_at' => 'date_format:Y-m-d H:i:s',
        ];

        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }

        /**
         * @var  Notification $entry
         */
        if (isset($data['id'])) {
            $entry = Notification::find($data['id']);
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
            $entry = new Notification();
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
        $entry = Notification::find($id);
        $entry->status = $req->status ? 'unread' : 'new';
        $entry->read_at=Carbon::now();
        $entry->save();

        return [
            'code' => 200,
            'message' => 'Đã lưu'
        ];
    }

    /**
     * Ajax data for index page
     * @uri  /xadmin/notifications/data
     * @return  array
     */
    public function data(Request $req)
    {

        $query = Notification::query()->with(['users'])
            ->orderBy('created_at', 'desc');
        $users = User::with(['notification','roles'])->orderBy('username')->get();
        if ($req->keyword) {
            $query->where('title', 'LIKE', '%' . $req->keyword. '%');
        }
        if ($req->username) {
            $query->whereHas('users', function ($q) use ($req) {
                $q->where('username', 'LIKE', '%' . $req->username . '%');
            });
        }
        $query->createdIn($req->created);
        $limit = 25;

        if ($req->limit) {
            $limit = $req->limit;
        }
        $entries = $query->paginate($limit);
        $data = [];

        foreach ($entries as $entry) {
            foreach ($users as $user) {
                if ($entry->user_id == $user->id) {
                    $entry->user_name = $user->username;
                }
                foreach($user->roles as $role)
                {
                    if($entry->user_id==$user->id)
                    {
                        $entry->role=$role->role_name;

                    }
                }

            }

            $user = Auth::user();
            foreach ($user->roles as $role) {

                if ( $role->role_name=='Super Administrator') {
                    $data[] = [
                        'id' => $entry->id,
                        'user_id' => $entry->user_id,
                        'read_at' => $entry->read_at,
                        'status' => $entry->status,
                        'title' => $entry->title,
                        'url' => $entry->url,
                        'content' => $entry->content,
                        'created_at' => $entry->created_at,
                        'updated_at' => $entry->updated_at,
                        'username' => $entry->user_name,
                        'role'=>$entry->role
                    ];
                }
            }

        }
        return [
            'code' => 0,
            'data' => [
                'entries' => $data,
            ],
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
                'totalRecord' => $entries->count(),
            ]
        ];


    }

    public function notification(Request $req)
    {
        $auth=Auth::user();
        $query = Notification::query()->where('user_id',$auth->id)->orderBy('created_at', 'desc');
        $users = User::with(['notification'])->orderBy('username')->get();
        if ($req->keyword) {
            //$query->where('title', 'LIKE', '%' . $req->keyword. '%');
        }

        $query->createdIn($req->created);
        $limit = 10;

        if ($req->limit) {
            $limit = $req->limit;
        }
        $entries = $query->paginate($limit);
        $dataSuper=[];
        foreach ($entries as $dataSuperAdmin)
        {
            if($dataSuperAdmin->status=='new' && $dataSuperAdmin->title=='File download plan')
            {
                $dataSuper[]=$dataSuperAdmin->title;
            }
        }
        $data = [
        ];
        $status=1;
        $notification=Notification::query()->where('status','=','new')->count();

        $notificationSuperAdmin=Notification::query()->whereNotIn('title',$dataSuper)->where('status','=','new')->count();
        $admin=Notification::query()->where('title','=','Yêu cầu xóa thiết bị')
            ->Where('status','=','new')
            ->count();
        $it=Notification::query()->where('title','=','File download plan')->where('user_id',$auth->id)->where('status','=','new')->count();

        foreach ($entries as $entry) {
            foreach ($users as $user) {
                if ($entry->user_id == $user->id) {
                    $entry->user_name = $user->username;
                }
            }
            $user = Auth::user();
            foreach ($user->roles as $role) {
                if($entry->title=='Yêu cầu cấp quyền' || $entry->title=='Yêu cầu xóa thiết bị' )
                {


                    if ( $role->role_name=='Super Administrator') {
                        $status=2;
                        $data[] = [
                            'id' => $entry->id,
                            'user_id' => $entry->user_id,
                            'read_at' => $entry->read_at,
                            'status' => $entry->status,
                            'title' => $entry->title,
                            'url' => $entry->url,
                            'content' => $entry->content,
                            'created_at' => $entry->created_at,
                            'updated_at' => $entry->updated_at,
                            'username' => $entry->user_name,
                        ];
                    }
                }
                if($entry->title=='Yêu cầu xóa thiết bị')
                {

                    if ( $role->role_name=='School Admin') {
                        $status=3;

                        $data[] = [
                            'id' => $entry->id,
                            'user_id' => $entry->user_id,
                            'read_at' => $entry->read_at,
                            'status' => $entry->status,
                            'title' => $entry->title,
                            'url' => $entry->url,
                            'content' => $entry->content,
                            'created_at' => $entry->created_at,
                            'updated_at' => $entry->updated_at,
                            'username' => $entry->user_name,
                        ];

                    }

                }
                if($entry->title=='File download plan' && $role->role_name=='IT')
                {

                    $plan=Plan::where('name','=',$entry->content)->where('user_id',$auth->id)->first();
                    $status=4;
                    $data[] = [
                        'plan_id'=>@$plan['id'],
                        'id' => $entry->id,
                        'user_id' => $entry->user_id,
                        'read_at' => $entry->read_at,
                        'status' => $entry->status,
                        'title' => $entry->title,
                        'url' => $entry->url,
                        'content' => $entry->content,
                        'created_at' => $entry->created_at,
                        'updated_at' => $entry->updated_at,
                        'username' => $entry->user_name,
                    ];

                }

            }
        }
        return [
            'code' => 0,
            'data' =>[
                'entries'=>$data,
                'notification'=>$notification,
                'admin'=>$admin,
                'status'=>$status,
                'it'=>$it,
                'notificationSuperAdmin'=>$notificationSuperAdmin

            ]

        ];

    }

    public function export()
    {
        $keys = [
            'type' => ['A', 'type'],
            'url' => ['B', 'url'],
            'channel' => ['C', 'channel'],
            'status' => ['D', 'status'],
            'content' => ['E', 'content'],
            'title' => ['F', 'title'],
            'sent_at' => ['G', 'sent_at'],
        ];

        $query = Notification::query()->orderBy('id', 'desc');

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
        Notification::whereIn('id', $ids)->delete();
        return [
            'code' => 0,
            'message' => 'Đã xóa'
        ];
    }

}
