<?php

namespace App\Console\Commands;

use App\Helpers\PermissionField;
use App\Helpers\PhpDoc;
use App\Imports\DeviceImport;
use App\Imports\TeacherImport;
use App\Jobs\SendMailPassword;
use App\Models\GroupPermission;
use App\Models\Inventory;
use App\Models\Permission;
use App\Models\PermissionDetail;
use App\Models\Product;
use App\Models\RoleHasPermission;
use App\Models\User;
use App\Support\CallApi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

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

        PermissionDetail::orderBy('id','desc')->update(['is_admin'=>'No']);
        dd(1);
        $sheets = Excel::toCollection(new DeviceImport(), "iTO-data.xlsx", 'excel-import');
        $query = '';
        foreach ($sheets as $sheet){
            foreach ($sheet as $key =>$row){
               if($key > 0){

                   if($row[0]){
                       $query = $query . 'Update li_bank_gaps set question="' .$row[3] . '" where id=' .$row[0] .';'.PHP_EOL;
                   }

               }

            }
        }
        file_put_contents(public_path('test.text'), $query);

        echo($query);
        dd(1);
        Excel::store(new TeacherImport([]), 'teacher.xlsx', 'excel-export');
        dd(1);




        $content = [
            'full_name' =>'abvv',
            'username'=>'quangtrung',
            'password'=>'abc'
        ];
        dispatch(new SendMailPassword('trung.nguyen@blueocean.net.vn', 'Test thu',$content));
        dd(1);
        dd(parse_url('http://localhost:8888/xadmin/request_roles/edit?id=45'));
        $userPermissions = [];
        $user = User::find(2);
        $roles = $user->roles;
        $roleIds = [];
        foreach ($roles as $role){
            $roleIds[] = $role->id;
        }

        $rolePermissions = RoleHasPermission::whereIn('role_id', $roleIds)
            ->with(['permission'])->get();

        foreach ($rolePermissions as $rolePermission){
            $userPermissions[$rolePermission->permission->code] = 1;
        }

        dd($userPermissions);



        $groupPermissions = GroupPermission::with(['permissions.roles', 'permissions', 'childs'])
            ->get();

        $user = User::find(2);
        $roles = $user->roles;
        $menus = [];
        $isAdmin = 0;

        foreach ($roles as $role) {
            if ($role->role_name == 'Super Administrator') {
                $isAdmin = 1;
            }
        }

        if($isAdmin == 1){
            foreach ($groupPermissions as $groupPermission) {

                if ($groupPermission->childs->count() > 0) {

                    $menu = [
                        "name" => $groupPermission->name,
                        "icon" => $groupPermission->icon,
                        'url' => $groupPermission->url,
                        'subs' => [],
                    ];
                    foreach ($groupPermission->childs as $child) {
                        $menu['subs'][] = [
                            "name" => $child->name,
                            "icon" => $child->icon,
                            'url' => $child->url,
                        ];
                    }
                }else{
                    $menu = [
                        "name" => $groupPermission->name,
                        "icon" => $groupPermission->icon,
                        'url' => $groupPermission->url
                    ];
                }

                $menus[] = $menu;

            }
        }else{
            foreach ($groupPermissions as $groupPermission) {
                $check = 0;
                $permissionRoles = explode(';', $groupPermission->role_id);
                foreach ($permissionRoles as $permissionRole){
                    foreach ($roles as $role){
                        if($permissionRole == $role->id){
                            $check = 1;
                        }
                    }
                }

                if($check == 1){
                    if ($groupPermission->childs->count() > 0) {

                        $menu = [
                            "name" => $groupPermission->name,
                            "icon" => $groupPermission->icon,
                            'url' => $groupPermission->url,
                            'subs' => [],
                        ];

                        foreach ($groupPermission->childs as $child) {
                            $menu['subs'][] = [
                                "name" => $child->name,
                                "icon" => $child->icon,
                                'url' => $child->url,
                            ];
                        }
                    }else{
                        $menu = [
                            "name" => $groupPermission->name,
                            "icon" => $groupPermission->icon,
                            'url' => $groupPermission->url
                        ];
                    }

                    $menus[] = $menu;
                }

            }
        }


        dd($menus);

        $data = [
            'client_id' => env('CLIENT_ID'),
            'grant_type' => 'password',
            'username' => 'khoanda',
            'password' => '123@123a',
        ];
        // dd($data);
        $uri = env('SSO_URL') . "/connect/token";

        $res = CallApi::sendRequest('POST', $uri, $data, 'form_params');
        $body = json_decode($res['body'], true);

        $data = [
            'access_token' => $body['access_token']
        ];
        // dd($data);
        $uri = env('SSO_URL') . "/connect/userinfo";

        $res = CallApi::sendRequest('POST', $uri, $data, 'form_params');
        $userInfo = json_decode($res['body'], true);

        $userData = [
            'username' => $userInfo['name'],
            'password' => '123456',
            'full_name' => $userInfo['ismartuser']['fullname'],
            'email' => $userInfo['ismartuser']['email'],
            'sso_id' => $userInfo['sub'],
            'state' => 1,
        ];

        User::updateOrCreate([
            'sso_id' => $userInfo['sub']
        ], $userData);


        dd($body);

        $time_start = microtime(true);
        $zip_file = public_path('invoices.zip');
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->setPassword('123456');
        $invoice_file = 'sample.pdf';
        $zip->addFile(public_path('Sample-Video-File-For-Testing.mp4'), 'Sample-Video-File-For-Testing.mp4');
        $zip->addFile(public_path('Sample-Video-File-For-Testing - Copy.mp4'), 'Sample-Video-File-For-Testing - Copy.mp4');
        /*$zip->setEncryptionName('test.pdf', \ZipArchive::EM_AES_256, '123456');*/
        $zip->close();

        echo 'Total execution time in seconds: ' . (microtime(true) - $time_start);

    }
}
