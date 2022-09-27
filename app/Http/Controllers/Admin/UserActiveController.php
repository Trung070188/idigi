<?php

namespace App\Http\Controllers\Admin;

use App\Models\AllocationContent;
use App\Models\AllocationContentSchool;
use App\Models\Course;
use App\Models\SchoolCourse;
use App\Models\SchoolCourseUnit;
use App\Models\User;
use App\Models\UserCourseUnit;
use App\Models\UserUnit;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\School;
use App\Models\Unit;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class UserActiveController extends AdminBaseController
{
    public static $menus = [
//        [
//            'name' => 'School License',
//            'icon' => 'fa fa-shopping-cart',
//            'url' => '/xadmin/school_license/index',
//        ]
    ];

    public function userActive()
    {
        $user = Auth::user();
        $component = 'UserDeactive';
        $title = 'User Active';
        $jsonData = [
        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }
}
