<?php

namespace App\Console\Commands;

use App\Helpers\PhpDoc;
use App\Models\Inventory;
use App\Models\Lesson;
use App\Models\LessonInventory;
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

                    $img = '';
                    $physicalPath = '';
                    $virtualPath = '';

                    if($inventory->image){
                        $path = str_replace('\\', '/', $inventory->image);
                        $paths = pathinfo($path);

                        $dir = public_path("files/attachments".$paths['dirname']);

                        if (!is_dir($dir)) {
                            mkdir($dir, 0755, true);
                            echo 1111111;
                        }

                        try {
                            $img = '/files/attachments'.str_replace('/files', '', $inventory->image);
                            file_put_contents(public_path($img), file_get_contents(env('OLD_DOMAIN').$inventory->image));

                        }
                        catch (\Exception $e) {
                            echo $e->getMessage();
                        }


                    }

                    if($inventory->virtual_path){
                        $path = str_replace('\\', '/', $inventory->virtual_path);
                        $paths = pathinfo($path);


                        $dir = public_path("files/attachments".$paths['dirname']);

                        if (!is_dir($dir)) {
                            mkdir($dir, 0755, true);
                        }

                        try {
                            $virtualPath = '/files/attachments'.str_replace('/files', '', $inventory->virtual_path);
                            file_put_contents(public_path($virtualPath), file_get_contents(env('OLD_DOMAIN').$inventory->virtual_path));
                            $physicalPath = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']).$virtualPath;

                        }
                        catch (\Exception $e) {
                            echo $e->getMessage();
                        }

                    }

                    $newInventory = [
                        'physical_path' => $physicalPath,
                        'virtual_path' => $virtualPath,
                        'enabled' => $inventory->enabled,
                        'image' => $img,
                        'grade' => $inventory->grade,
                        'name' => $inventory->name,
                        'subject' => $inventory->subject,
                        'type' => $inventory->type,
                        'created_at' => $inventory->created_date,
                        'updated_at' => $inventory->last_modified_date,
                        'created_by' => @$userCreate->id,
                        'updated_by' => @$userUpdate->id,
                        'old_id' => $inventory->id,
                        'rating' => $inventory->rating,
                        'duration' => $inventory->duration,
                        'link_webview' => $inventory->link_webview,
                        'slideshows' => $inventory->slideshows,
                        'tags' => $inventory->tags,
                    ];

                    Inventory::updateOrCreate([
                        'old_id' => $inventory->id
                    ], $newInventory);

                    echo 'Sync inventory: '.$inventory->id.PHP_EOL;
                }
            });

        \DB::connection('mysql2')->table('lessons')
            ->chunkById(100, function ($lessons) {
                foreach ($lessons as $lesson){
                   if($lesson->structure){
                       $structure = json_decode($lesson->structure, true);
                       if($structure){
                           if(@$structure['sublesson']){
                               $inventories= $structure['sublesson'];
                               $newLesson = Lesson::where('old_id', $lesson->id)->first();
                               foreach ($inventories as $inventory){
                                    $newInventory = Inventory::where('old_id', $inventory['idSublesson'])->first();
                                    if($newLesson && $newInventory){
                                        LessonInventory::updateOrCreate([
                                            'lesson_id' => $lesson->id,
                                            'inventory_id' => $inventory->id,
                                        ],[
                                            'lesson_id' => $lesson->id,
                                            'inventory_id' => $inventory->id,
                                        ]);
                                    }
                               }

                           }
                       }
                   }

                    echo 'Sync lesson inventory: '.$lesson->id.PHP_EOL;
                }
            });
    }
}
