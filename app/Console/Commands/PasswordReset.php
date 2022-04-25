<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PasswordReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PasswordReset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
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
        $adminEmail = 'admin@localhost';
        $user = User::query()->where('email', $adminEmail)->first();

        if (!$user) {
            $user = new User();
            $user->name = 'admin';
            $user->email = $adminEmail;
        }

        $this->info("Enter new password for admin: {$adminEmail}");
        $password = $this->ask('Enter password');
        $user->password = Hash::make($password);
        $user->save();
        $userGroup = DB::selectOne('SELECT * FROM user_roles WHERE user_id=? AND role_id=?', [
            $user->id, Role::SUPER_USER,
        ]);

        if (!$userGroup) {
            DB::table('user_roles')->insert([
                'user_id' => $user->id,
                'role_id' => Role::SUPER_USER,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $this->info("New password is: {$password}");
    }
}
