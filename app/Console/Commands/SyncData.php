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

        //Đồng bộ file và inventory
        \DB::connection('mysql2')->table('inventories')
            ->where('id', '>',209)
            ->chunkById(100, function ($inventories) {
                foreach ($inventories as $inventory){
                    $userCreate = User::where('username', $inventory->created_by)->first();
                    $userUpdate = User::where('username', $inventory->last_modified_by)->first();

                    $img = '';
                    $physicalPath = '';
                    $virtualPath = '';
                    $fileImageId = NULL;
                    $fileAssetId = NULL;

                    if($inventory->image){
                        $path = str_replace('\\', '/', $inventory->image);
                        $paths = pathinfo($path);

                        $dir = public_path("files/attachments".$paths['dirname']);

                        if (!is_dir($dir)) {
                            mkdir($dir, 0755, true);
                        }

                        try {
                            $img = '/files/attachments'.$inventory->image;
                            file_put_contents(public_path($img), file_get_contents(env('OLD_DOMAIN').$inventory->image));
                            $fileImageId = $this->insertFile($img, 1);

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
                            $virtualPath = '/files/attachments'.$inventory->virtual_path;
                            ini_set('memory_limit','2048M');
                            file_put_contents(public_path($virtualPath), file_get_contents(env('OLD_DOMAIN').$inventory->virtual_path));
                            $physicalPath = public_path($virtualPath);
                            $fileAssetId = $this->insertFile($virtualPath, 0);

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
                        'file_image_id' => $fileImageId,
                        'file_asset_id' => $fileAssetId,
                    ];

                    Inventory::updateOrCreate([
                        'old_id' => $inventory->id
                    ], $newInventory);

                    echo 'Sync inventory: '.$inventory->id.PHP_EOL;
                }
            });
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
                            $oldStructure['sublesson'][$key1]['full_link'] = url(@$inventory->virtual_path);
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
                        'name' => $lesson->subject.'_'.$lesson->grade,
                    ];
                    $course = Course::updateOrCreate($courseData, $courseData);

                    $unitData = [
                        'course_id' => $course->id,
                        'name' => @$oldStructure['UnitName']
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
                        'created_at' => $lesson->created_date,
                        'updated_at' => $lesson->last_modified_date,
                        'created_by' => @$userCreate->id,
                        'updated_by' => @$userUpdate->id,
                    ];


                    Lesson::updateOrCreate([
                        'old_id' => $lesson->id
                    ], $newLesson);

                    echo 'Sync lesson: ' . $lesson->id . PHP_EOL;
                }
            });

        //Đồng bộ inventory và lesson
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
                                            'lesson_id' => @$newLesson->id,
                                            'inventory_id' => @$newInventory->id,
                                        ],[
                                            'lesson_id' => @$newLesson->id,
                                            'inventory_id' => @$newInventory->id,
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

    protected function insertFile($path, $isImage = 0)
    {
        $newFilePath = public_path($path);
        $info = pathinfo($path);
        $file = new File();
        $file->type = \Illuminate\Support\Facades\File::type($newFilePath);
        $file->hash = sha1($newFilePath);
        $file->url = url($path);
        $file->is_image = $isImage;
        $file->size = \Illuminate\Support\Facades\File::size($newFilePath);
        $file->name = $info['filename'];
        $file->path = $newFilePath;
        $file->extension = \Illuminate\Support\Facades\File::extension($newFilePath);
        $file->save();

        return $file->id;
    }
}
