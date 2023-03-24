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

class UpdatePositionUnit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-position-unit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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

        $courses = Course::with(['unit1'=> function($q){
            $q->orderBy('position', 'ASC');
            $q->orderBy('id', 'ASC');
        }])->get();

        foreach ($courses as $course){
            if($course->unit1){
                foreach ($course->unit1 as $key => $unit){
                    if(!$unit->position){
                        $unit->position = $key + 1;
                        $unit->save();
                    }
                }
            }
            echo 'Update course: '.$course->id .PHP_EOL;
        }
    }

}
