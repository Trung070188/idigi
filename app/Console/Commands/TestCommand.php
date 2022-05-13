<?php

namespace App\Console\Commands;

use App\Helpers\PhpDoc;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test1';

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

        $time_start = microtime(true);
        $zip_file = public_path('invoices.zip');
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->setPassword('123456');
        $invoice_file = 'sample.pdf';
        $zip->addFile( public_path('Sample-Video-File-For-Testing.mp4'),'Sample-Video-File-For-Testing.mp4');
        $zip->addFile( public_path('Sample-Video-File-For-Testing - Copy.mp4'),'Sample-Video-File-For-Testing - Copy.mp4');
        /*$zip->setEncryptionName('test.pdf', \ZipArchive::EM_AES_256, '123456');*/
        $zip->close();

        echo 'Total execution time in seconds: ' . (microtime(true) - $time_start);

    }
}
