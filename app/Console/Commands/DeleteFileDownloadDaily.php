<?php

namespace App\Console\Commands;

use App\Helpers\PhpDoc;
use App\Models\Course;
use App\Models\DownloadLessonFile;
use App\Models\DownloadLessonLog;
use App\Models\File;
use App\Models\Inventory;
use App\Models\Lesson;
use App\Models\LessonInventory;
use App\Models\Product;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteFileDownloadDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete-file-download-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Xóa file download hàng ngày của user';

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

        $logLessons = DownloadLessonFile::where('is_deleted_file', '<>', 1)
            ->where("created_at", "<", Carbon::now()->addHours(-24))
            ->get();


        foreach ($logLessons as $logLesson){
            if($logLesson->path){
                if(is_file($logLesson->path)){
                    try {
                        unlink($logLesson->path);


                    }
                    catch (\Exception $e) {
                        echo $e->getMessage();
                    }
                }
                $logLesson->is_deleted_file = 1;
                $logLesson->save();
                echo 'Delete file in record: '. $logLesson->id.PHP_EOL;
            }

        }

    }


}
