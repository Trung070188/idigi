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

class UpdateSubjectUnit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-subject-unit';

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

        $units = Unit::with(['course'])->get();

        foreach ($units as $unit) {
            $unit->subject = @$unit->course->subject;
            $unit->save();
            echo 'Update id: '. $unit->id.PHP_EOL;

        }
    }

}
