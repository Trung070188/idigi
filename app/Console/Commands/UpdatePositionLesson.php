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

class UpdatePositionLesson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-position-lesson';

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

        $units = Unit::with(['lessons'=> function($q){
            $q->orderBy('position', 'ASC');
            $q->orderBy('id', 'ASC');
        }])->get();

        foreach ($units as $unit){
            if($unit->lessons){
                foreach ($unit->lessons as $key => $lesson){
                    if(!$lesson->position){
                        $lesson->position = $key + 1;
                        $lesson->save();
                    }
                }
            }
            echo 'Update course: '.$unit->id .PHP_EOL;
        }
    }

}
