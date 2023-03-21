<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AllocationContentSchool;
use App\Models\Lesson;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class LessonController extends Controller
{


    public function __construct()
    {

    }

    public function getAllLesson(Request  $request){

        $token = $request->bearerToken();

        $decoded = JWT::decode($token, new Key(env('SECRET_KEY'), 'HS256'));

        $user = User::where('username', $decoded->username)->first();
        $unitIds = [];
        $schoolId = $user->school_id;
        $isSuperAdmin = 0;

        foreach ($user->roles as $role) {
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
            if ($role->role_name == 'School Admin') {
                $contents = AllocationContentSchool::where('school_id', $schoolId)
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

            if ($role->role_name == 'Super Administrator') {
                $isSuperAdmin = 1;
            }
        }

        $query = Lesson::query()->with(['user_units', 'inventories', 'unit1', 'unit1.course'])
            ->orderBy('position', 'ASC')
            ->orderBy('id', 'ASC');

        if($isSuperAdmin == 0){
            $query = $query->whereIn('unit_id', $unitIds);
        }

        $lessons = $query->get();
        $data = [];

        foreach ($lessons as $lesson){
            $inventoryData = [];

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
                    "subLessons" => $inventoryData,

                ];
                $data[] = $structure;
            }

        }

        return [
            'code' => 0,
            'msg' => 'Success',
            'lesson' => $data
        ];

    }
}
