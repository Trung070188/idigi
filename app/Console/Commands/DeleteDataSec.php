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
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteDataSec extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete-data-sec';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Xóa file cap2';

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

        $data = Inventory::where('level', 'sec')->get();


        foreach ($data as $item){
            if($item->physical_path){
                if(is_file($item->physical_path)){
                    try {
                        unlink($item->physical_path);


                    }
                    catch (\Exception $e) {
                        echo $e->getMessage();
                    }
                }

                echo 'Delete file in record: '. $item->id.PHP_EOL;
            }

        }

    }


}
