<?php
/**
 * @author
 * @date: 3:20 PM
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AppController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DashboardController extends AdminBaseController
{

    public function index()
    {

        $title = 'Thống kê';
        $component = 'DashboardIndex';


        return view('admin.layouts.vue', compact('title', 'component'));
    }

}
