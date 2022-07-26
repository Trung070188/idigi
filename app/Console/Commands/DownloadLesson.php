<?php
/**
 * Code generator
 * @author quantm@ominext.com
 */
namespace App\Console\Commands;


use App\Models\Lesson;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DownloadLesson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download-lesson';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        $this->downloadLesson();
    }

    public function downloadLesson()
    {
        ob_get_clean();

        /*if ($request->isMethod('POST')) {
            throw new MethodNotAllowedException("405");
        }*/

        $user = User::find(123);
        $deviceId = 126;
        $userDevice = UserDevice::where('user_id', $user->id)->where('id', $deviceId)->first();
        $password = @$userDevice->secret_key;

        $y = date('Y');
        $m = date('m');
        $d = date('d');
        $dir = "files/downloads/{$y}/{$m}/{$d}/{$user->id}";

        if (!is_dir(public_path($dir))) {
            mkdir(public_path($dir), 0755, true);
        }

        $lessonIds = [331,332,333,334,335,336,337,338,339,340,341,314,315];
        $lessons = Lesson::whereIn('id',  $lessonIds)
            ->with(['inventories'])->get();


        $filenameAll = uniqid(time().rand(10, 100));
        $pathZipAll = $dir . '/all_lessons_'.$filenameAll.'.zip';
        $zipFileAll = public_path($pathZipAll);
        $zipAll = new \ZipArchive();
        $zipAll->open($zipFileAll, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);


        foreach ($lessons as $key => $lesson) {
            $filename = uniqid(time().rand(10,100));
            $name = explode(':', $lesson->name);
            $zip_file = public_path($dir . '/'.$name[0].'.zip');
            $zip = new \ZipArchive();
            $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
            $structure = json_decode($lesson->structure, true);


            if ($lesson->inventories) {
                foreach ($lesson->inventories as $inventory) {
                    $icon = 'Icons/' . basename(public_path($inventory->image));
                    $link = basename(public_path($inventory->virtual_path));

                    if(file_exists(public_path($inventory->image)) && is_file(public_path($inventory->image))){
                        $zip->addFile(public_path($inventory->image), $icon);
                        $zip->setEncryptionName($icon, \ZipArchive::EM_AES_256, $password);
                    }
                    if(file_exists(public_path($inventory->virtual_path)) && is_file(public_path($inventory->virtual_path))){
                        $zip->addFile(public_path($inventory->virtual_path), $link);
                        $zip->setEncryptionName($link, \ZipArchive::EM_AES_256, $password);
                    }


                }
            }

            Storage::put($dir . '/lesson_detail'.$filename.'.txt', json_encode($structure));

            $zip->addFile(storage_path('app/' . $dir . '/lesson_detail'.$filename.'.txt'), 'lesson_detail.txt');
            $zip->setEncryptionName('lesson_detail.txt', \ZipArchive::EM_AES_256,$password);
            $zip->close();

            $zipAll->addFile($zip_file, $name[0].'.zip');
            $zipAll->setEncryptionName('/'.$name[0].'.zip', \ZipArchive::EM_AES_256, $password);

        }

        $zipAll->close();
        echo url($pathZipAll);

        /*return [
            'code' => 0,
            'url' => url($pathZipAll)
        ];*/

    }

}
