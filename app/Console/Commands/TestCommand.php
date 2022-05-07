<?php

namespace App\Console\Commands;

use App\Helpers\PhpDoc;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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

        $zip_file = public_path('invoices.zip');
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->setPassword('123456');
        $invoice_file = 'sample.pdf';
        $zip->addFile( storage_path($invoice_file),'test.pdf');
        $zip->setEncryptionName('test.pdf', \ZipArchive::EM_AES_256, '123456');
        $zip->close();

    }
}
