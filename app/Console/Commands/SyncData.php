<?php

namespace App\Console\Commands;

use App\Helpers\PhpDoc;
use App\Models\Inventory;
use App\Models\Lesson;
use App\Models\Product;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SyncData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync-from-old-data';

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
        \DB::connection('mysql2')->table('lessons')
            ->chunkById(100, function ($lessons) {
                foreach ($lessons as $lesson){
                    $userCreate = User::where('username', $lesson->created_by)->first();
                    $userUpdate = User::where('username', $lesson->last_modified_by)->first();
                    $newLesson = [
                        'enabled' => $lesson->enabled,
                        'grade' => $lesson->grade,
                        'name' => $lesson->name,
                        'rating' => $lesson->rating,
                        'shared' => $lesson->shared,
                        'structure' => $lesson->structure,
                        'subject' => $lesson->subject,
                        'unit' => $lesson->unit,
                        'number' => $lesson->number,
                        'customized' => $lesson->customized,
                        'old_id' => $lesson->id,
                        'created_at' => $lesson->created_date,
                        'updated_at' => $lesson->last_modified_date,
                        'created_by' => @$userCreate->id,
                        'updated_by' => @$userUpdate->id,
                    ];

                    Lesson::updateOrCreate([
                        'old_id' => $lesson->id
                    ], $newLesson);

                    echo 'Sync lesson: '.$lesson->id.PHP_EOL;
                }
            });



        \DB::connection('mysql2')->table('inventories')
            ->chunkById(100, function ($inventories) {
                foreach ($inventories as $inventory){
                    $userCreate = User::where('username', $inventory->created_by)->first();
                    $userUpdate = User::where('username', $inventory->last_modified_by)->first();
                    $newInventory = [
                        'enabled' => $inventory->enabled,
                        'grade' => $inventory->grade,
                        'name' => $inventory->name,
                        'subject' => $inventory->subject,
                        'type' => $inventory->type,
                        'created_at' => $inventory->created_date,
                        'updated_at' => $inventory->last_modified_date,
                        'created_by' => @$userCreate->id,
                        'updated_by' => @$userUpdate->id,
                        'old_id' => @$userUpdate->id,
                        'rating' => @$userUpdate->rating,
                        'duration' => @$userUpdate->duration,
                        'link_webview' => @$userUpdate->link_webview,
                        'slideshows' => @$userUpdate->slideshows,
                        'tags' => @$userUpdate->tags,
                    ];

                    Inventory::updateOrCreate([
                        'old_id' => $inventory->id
                    ], $newInventory);

                    echo 'Sync inventory: '.$inventory->id.PHP_EOL;
                }
            });

    }
}
