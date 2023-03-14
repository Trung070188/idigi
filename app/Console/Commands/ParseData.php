<?php

namespace App\Console\Commands;

use App\Helpers\PhpDoc;
use App\Models\Course;
use App\Models\File;
use App\Models\Inventory;
use App\Models\Lesson;
use App\Models\LessonInventory;
use App\Models\Product;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ParseData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse-from-old-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Đồng bộ data từ db cũ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        //Đồng bộ lesson
        \DB::connection('mysql2')->table('lessons')
            ->chunkById(100, function ($lessons) {
                foreach ($lessons as $lesson) {
                    $userCreate = User::where('username', $lesson->created_by)->first();
                    $userUpdate = User::where('username', $lesson->last_modified_by)->first();
                    $oldStructure = json_decode($lesson->structure, true);
                    $name = explode(':', $lesson->name);
                    if(@$oldStructure['sublesson']){
                        foreach ($oldStructure['sublesson'] as $key1 => $subLesson){
                            $inventory = Inventory::where('old_id',@$subLesson['idSublesson'] )->first();
                            $link = '';

                            if($inventory && @$inventory->virtual_path){
                                $link = basename(public_path($inventory->virtual_path));
                            }

                            $oldStructure['sublesson'][$key1]['link'] = $link;
                            $oldStructure['sublesson'][$key1]['full_link'] = url('/api/download/inventory/' . $inventory->id);
                        }
                    }

                    $structure = [
                        "idSubject" => @$oldStructure['idSubject'],
                        "codeSubject" => $lesson->subject,
                        "nameSubject" => "iSMART ".$lesson->subject,
                        "grade" => $lesson->grade,
                        "idUnit" => $lesson->unit,
                        "titleUnit" => $lesson->number,
                        "nameUnit" => @$oldStructure['UnitName'],
                        "idLesson" => $lesson->id,
                        "codeLesson" =>  @$name[0],
                        "titleLesson" => @$oldStructure['LessonTitle'],
                        "nameLesson" => @$oldStructure['nameLesson'],
                        "subLessons" =>@$oldStructure['sublesson']
                    ];
                    $courseData = [
                        'course_name' => $lesson->subject.'_'.$lesson->grade,
                        'grade' => $lesson->grade,
                        'subject' => $lesson->subject,
                    ];
                    $course = Course::updateOrCreate(['course_name' => $lesson->subject.'_'.$lesson->grade], $courseData);

                    $unitData = [
                        'course_id' => $course->id,
                        'unit_name' => @$oldStructure['UnitName']
                    ];



                    $unit = Unit::updateOrCreate($unitData, $unitData);
                    $newLesson = [
                        'enabled' => $lesson->enabled,
                        'grade' => $lesson->grade,
                        'name' => $lesson->name,
                        'rating' => $lesson->rating,
                        'shared' => $lesson->shared,
                        'structure' => json_encode($structure),
                        'subject' => $lesson->subject,
                        'unit' => $lesson->unit,
                        'unit_id' => $unit->id,
                        'course_id' => $course->id,
                        'unit_name' => @$oldStructure['UnitName'],
                        'number' => $lesson->number,
                        'customized' => $lesson->customized,
                        'old_id' => $lesson->id,
                        'level' => 'pri',
                        'created_at' => $lesson->created_date,
                        'updated_at' => $lesson->last_modified_date,
                        'created_by' => @$userCreate->id,
                        'updated_by' => @$userUpdate->id,
                    ];


                    Lesson::updateOrCreate([
                        'old_id' => $lesson->id,
                        'level' => 'pri',
                    ], $newLesson);

                    echo 'Sync lesson: ' . $lesson->id . PHP_EOL;
                }
            });


    }

}
