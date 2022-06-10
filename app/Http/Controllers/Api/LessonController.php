<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;

class LessonController extends Controller
{


    public function __construct()
    {

    }

    public function getAllLesson(){
        $lessons = Lesson::orderBy('name', 'ASC')->get();
        $data = [];

        foreach ($lessons as $lesson){
            if($lesson->structure){
                $data[] = json_decode($lesson->structure, true);
            }
        }

        return [
            'code' => 0,
            'msg' => 'Success',
            'lesson' => $data
        ];

    }
}
