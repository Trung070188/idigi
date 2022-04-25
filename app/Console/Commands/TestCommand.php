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
        $inventory = new Inventory();
        $inventory->name = 'Chi nhánh vinhome skylake';
        $inventory->status = 1;
        $inventory->save();
    }
}
