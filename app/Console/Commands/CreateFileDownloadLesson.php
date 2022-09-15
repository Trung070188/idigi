<?php

namespace App\Console\Commands;

use App\Helpers\PhpDoc;
use App\Jobs\UpdateDownloadInventory;
use App\Jobs\UpdateDownloadLessonFile;
use App\Models\Course;
use App\Models\DownloadLessonIt;
use App\Models\DownloadLessonLog;
use App\Models\File;
use App\Models\Inventory;
use App\Models\Lesson;
use App\Models\LessonInventory;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\ZipPlanLesson;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreateFileDownloadLesson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-file-download-lesson-it';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tạo file download lesson cho it';
    protected $lessonIds = [];

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

        $planLessons = ZipPlanLesson::where('status', NULL)->with('plan')->get();
        $infos = [];


        foreach ($planLessons as $planLesson){
            $lessonIds = explode(',', $planLesson->lesson_ids);
            $planLesson->status= 'inprogress';
            $planLesson->save();
            $info = [
                'user_id' => $planLesson->user_id,
                'ip_address' => NULL,
                'user_agent' => NULL,
                'device_uid' => NULL,
                'lesson_ids' =>  $lessonIds,
                'plan_id' =>  $planLesson->id,
                'secret_key' =>  @$planLesson->plan->secret_key
            ];

            $infos[] = $info;
        }

        foreach ($infos as $info){
            $url = $this->createFile($info);
            $notify = new Notification();
            $notify->status = 'new';
            $notify->content = "File download";
            $notify->channel = 'inapp';
            $notify->user_id = $info['user_id'];
            $notify->url = $url;
            $notify->title = 'File download đã hoàn thành';
            $notify->save();
        }

    }

    public function createFile($info)
    {
        ob_get_clean();


        $password = env('SECRET_KEY') . '_' .  $info['secret_key'];

        $y = date('Y');
        $m = date('m');
        $d = date('d');
        $dir = "files/downloads/{$y}/{$m}/{$d}/{$info['user_id']}";

        if (!is_dir(public_path($dir))) {
            mkdir(public_path($dir), 0755, true);
        }

        $lessons = Lesson::whereIn('id', $info['lesson_ids'])
            ->with(['inventories'])->get();


        $filenameAll = uniqid(time() . rand(10, 100));
        $pathZipAll = $dir . '/all_lessons_' . $filenameAll . '.zip';
        $zipFileAll = public_path($pathZipAll);
        $zipAll = new \ZipArchive();
        $zipAll->open($zipFileAll, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $lessonLog = DownloadLessonLog::create([
            'user_id' => $info['user_id'],
            'ip_address' => $info['ip_address'],
            'user_agent' => $info['user_agent'],
            'device_uid' => $info['device_uid'],
            'lesson_ids' => implode(',', $info['lesson_ids']),
            'download_at' => Carbon::now(),
        ]);

        foreach ($lessons as $key => $lesson) {
            $filename = uniqid(time() . rand(10, 100));
            $name = explode(':', $lesson->name);
            $zip_file = public_path($dir . '/' . $name[0] . '.zip');
            $zip = new \ZipArchive();
            $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
            $structure = json_decode($lesson->structure, true);


            if ($lesson->inventories) {
                foreach ($lesson->inventories as $inventory) {
                    $icon = 'Icons/' . basename(public_path($inventory->image));
                    $link = basename(public_path($inventory->virtual_path));

                    if (file_exists(public_path($inventory->image)) && is_file(public_path($inventory->image))) {
                        $zip->addFile(public_path($inventory->image), $icon);
                        $zip->setEncryptionName($icon, \ZipArchive::EM_AES_256, $password);
                    }
                    if (file_exists(public_path($inventory->virtual_path)) && is_file(public_path($inventory->virtual_path))) {
                        $zip->addFile(public_path($inventory->virtual_path), $link);
                        $zip->setEncryptionName($link, \ZipArchive::EM_AES_256, $password);
                    }

                    $dataDownloadInventory = [
                        'user_id' => $info['user_id'],
                        'ip_address' => $info['ip_address'],
                        'user_agent' => $info['user_agent'],
                        'device_uid' => $info['device_uid'],
                        'lesson_id' => $lesson->id,
                        'download_at' => Carbon::now(),
                        'type' => 'cms',
                        'inventory_id' => $inventory->id
                    ];
                    UpdateDownloadInventory::dispatch($dataDownloadInventory);

                }
            }

            Storage::put($dir . '/lesson_detail' . $filename . '.txt', json_encode($structure));

            $zip->addFile(storage_path('app/' . $dir . '/lesson_detail' . $filename . '.txt'), 'lesson_detail.txt');
            $zip->setEncryptionName('lesson_detail.txt', \ZipArchive::EM_AES_256, $password);
            $zip->close();

            $dataLessonFile = [
                'download_lesson_log_id' => $lessonLog->id,
                'path' => $zip_file,
                'is_main' => 0,
                'is_deleted_file' => 0
            ];

            UpdateDownloadLessonFile::dispatch($dataLessonFile);

            $zipAll->addFile($zip_file, $name[0] . '.zip');
            $zipAll->setEncryptionName('/' . $name[0] . '.zip', \ZipArchive::EM_AES_256, $password);

        }

        $zipAll->close();

        $dataLessonFile = [
            'download_lesson_log_id' => $lessonLog->id,
            'path' => $zipFileAll,
            'is_main' => 1,
            'is_deleted_file' => 0
        ];
        UpdateDownloadLessonFile::dispatch($dataLessonFile);

        ZipPlanLesson::where('id',$info['plan_id'])->update([
            'url' => url($pathZipAll),
            'status' => 'done',
        ]);


        return url($pathZipAll);
    }
}
