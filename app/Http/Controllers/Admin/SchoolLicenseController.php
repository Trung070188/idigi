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


class SchoolLicenseController extends AdminBaseController
{
    public static $menus = [
        [
            'name' => 'School License',
            'icon' => 'fa fa-shopping-cart',
            'url' => '/xadmin/school_license/index',
        ]
    ];

    public function licenseExpired()
    {
        $user = Auth::user();
        $check = 1;
        if($user->roles){
            foreach ($user->roles as $role){
                if($role->role_name == "Teacher" || $role->role_name == "School Admin"){
                    $school = School::where('id', $user->school_id)->first();

                    if($school->license_to < Carbon::now() ){
                       $check = 0;
                    }
                }
            }
        }

        if($check == 1){
            return redirect('/xadmin/request_role/index');
        }

        $component = 'LicenseExpired';
        $title = 'License Expired';

        $jsonData = [
            'data' => ''
        ];
        return view('admin.layouts.vue', compact('title', 'component', 'jsonData'));
    }
}
