<?php

namespace App\Console\Commands;

use App\Models\Permission;
use Illuminate\Console\Command;
use \ReflectionMethod;

class PermissionCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission {action}';

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
     * @return int
     */
    public function handle()
    {
        $action = $this->argument('action');
        $this->$action();
        return 0;
    }

    private function dump()
    {
        $root = Permission::find(1);
        if (!$root) {
            $root->id = 1;
            $root->name = 'root';
            $root->class = '*';
            $root->action = '*';
            $root->display_name = 'Root Permission';
            $root->save();
        }


        $this->info("Starting pemission dump");
        $inserted = 0;
        $dir = app_path('Http/Controllers/Admin');
        $files = scandir($dir);
        $ignoredClass = [
            'AdminBaseController' => true,
        ];

        $baseClass = 'App\Http\Controllers\Admin\AdminBaseController';
        foreach ($files as $idx => $file) {
            if ($idx < 2) {
                continue;
            }

            $filename = $dir.'/'.$file;

            if (is_file($filename)) {
                $info = pathinfo($file);

                if (isset($info['filename'])) {
                    $controllerName = $info['filename'];

                    if (isset($ignoredClass[$controllerName])) {
                        continue;
                    }


                    $class = new \ReflectionClass('\App\Http\Controllers\Admin\\'.$controllerName);
                    $parent = $class->getParentClass();
                    if (!$parent || $parent->name !== $baseClass) {
                        continue;
                    }

                    $namespaces = explode("\\", $class->name);
                    $baseClassName = last($namespaces);
                    $baseClassName = str_replace('Controller', '', $baseClassName);

                    $parent = Permission::where('class', $class->name)->fisrt();
                    if (!$parent) {
                        $parent = new Permission();
                    }

                    $parent->name = $baseClassName;
                    $parent->class = $class->name;
                    $parent->action = '*';
                    $parent->parent_id = $root->id;
                    $parent->display_name = $baseClassName;
                    $parent->save();

                    $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
                    foreach ($methods as $method) {
                        if (substr($method->name, 0, 2) !== '__') {
                            $permission = Permission::where('class', $method->class)
                                ->where('action', $method->name)
                                ->first();

                            if (!$permission) {
                                $permission = new Permission();

                                $permission->class = $method->class;
                                $permission->action = $method->name;
                                $permission->name = $baseClassName. '.' . $method->name;
                                $permission->parent_id = $parent->id;
                                $permission->save();
                                $inserted++;
                            }

                        }

                    }


                }
            }
        }

        $this->info("INSERTED $inserted");
    }
}
