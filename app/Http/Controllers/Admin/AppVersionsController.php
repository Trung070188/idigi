<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\AppVersion;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class AppVersionsController extends AdminBaseController
{
    public static $menus = [
        [
            'name' => 'AppVersion',
            'icon' => 'fa fa-shopping-cart',
            'url' => '/xadmin/app_versions/index',
        ]
    ];

    /**
     * Index page
     * @uri  /xadmin/app_versions/index
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function index()
    {
        $title = 'AppVersion';
        $component = 'App_versionIndex';
        return component($component, compact('title'));
    }

    /**
     * Create new entry
     * @uri  /xadmin/app_versions/create
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function create(Request $req)
    {
        $component = 'App_versionForm';
        $title = 'Create app_versions';
        return component($component, compact('title'));
    }

    /**
     * @uri  /xadmin/app_versions/edit?id=$id
     * @throw  NotFoundHttpException
     * @return  View
     */
    public function edit(Request $req)
    {
        $id = $req->id;
        $entry = AppVersion::find($id);

        if (!$entry) {
            throw new NotFoundHttpException();
        }

        /**
         * @var  AppVersion $entry
         */

        $title = 'Edit';
        $component = 'App_versionForm';


        return component($component, compact('title', 'entry'));
    }

    /**
     * @uri  /xadmin/app_versions/remove
     * @return  array
     */
    public function remove(Request $req)
    {
        $id = $req->id;
        $entry = AppVersion::find($id);

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
     * @uri  /xadmin/app_versions/save
     * @return  array
     */
    public function save(Request $req)
    {
        if (!$req->isMethod('POST')) {
            return ['code' => 405, 'message' => 'Method not allow'];
        }

        $data = $req->get('entry');

        $rules = [
            'name' => 'required|max:45',
            'url' => 'max:255',
            'path' => 'max:255',
        ];

        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return [
                'code' => 2,
                'errors' => $v->errors()
            ];
        }

        /**
         * @var  AppVersion $entry
         */
        if (isset($data['id'])) {
            $entry = AppVersion::find($data['id']);
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
            $entry = new AppVersion();
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
        $entry = AppVersion::find($id);

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
     * @uri  /xadmin/app_versions/data
     * @return  array
     */
    public function data(Request $req)
    {
        $query = AppVersion::query()->orderBy('id', 'desc');

        if ($req->keyword) {
            //$query->where('title', 'LIKE', '%' . $req->keyword. '%');
        }

        $query->createdIn($req->created);


        $entries = $query->paginate(100);

        return [
            'code' => 0,
            'data' => $entries->items(),
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
            ]
        ];
    }

}
