<?php

namespace App\Console\Commands;

use App\Helpers\PhpDoc;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PostPublish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PostPublish';

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
        echo "Auto publish posts...";
        $now = date('Y-m-d H:i:s');
        $updated = DB::update("UPDATE posts SET `status`=1,publish_at=? WHERE `status`=0 AND publish_at IS NOT NULL AND publish_at<=?", [$now, $now]);
        echo " published $updated\n";

    }
}
