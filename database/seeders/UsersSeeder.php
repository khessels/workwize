<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    protected string $pw = '1234';
    protected string $email = 'admin@myshop.com';
    public function users(): array
    {
        $users                  = [];
        // note: permissions and roles are defined in the constants file

        $user['name']           = 'KC, the new Backend dev';
        $user['status']         = 'active';
        $user['email']          = $this->email;
        $user['password']       = Hash::make($this->pw);
        $user['super_admin']    = true;
        $user['roles']          = config('constants.roles');
        $user['permissions']    = config('constants.permissions');
        $users[]                = $user;

        $user['name']           = 'supplier';
        $user['status']         = 'active';
        $user['email_verified_at'] = Carbon::now()->toIso8601String();
        $user['email']          = 'supplier@myshop.com';
        $user['password']       = Hash::make($this->pw);
        $user['super_admin']    = false;
        $user['roles']          = [ ['name' => 'supplier'] ];

        $users[]                = $user;

        $user['name']           = 'customer';
        $user['status']         = 'active';
        $user['email_verified_at'] = Carbon::now()->toIso8601String();
        $user['email']          = 'customer@myshop.com';
        $user['password']       = Hash::make($this->pw);
        $user['super_admin']    = false;
        $user['user_type']      = "service";
        $user['roles']          = [ ['name' => 'customer'] ];

        $users[]                = $user;
        return $users;
    }
    public function run(): void
    {
        $superAdminPassword = $this->command->ask("Set admin password", $this->pw);
        $superAdminEmail = $this->command->ask("Set admin email", $this->email);
        $this->command->info('Credentials for Kees Hessels ( ' . $superAdminEmail . ' ): ' . $superAdminPassword);
        $this->command->info('*******');

        foreach($this->users() as $aUser){
            if($aUser['super_admin']){
                $aUser['email'] = $superAdminEmail;
                $aUser['password'] = Hash::make($superAdminPassword);;
            }

            $user = User::create([ 'name' => $aUser['name'], 'email' => $aUser['email'], 'password' => $aUser['password'] ]);
            foreach($aUser['roles'] as $role ){
                $user->assignRole( $role['name'] );
            }
            foreach($aUser['permissions'] as $permission ){
                $user->givePermissionTo( $permission );
            }
        }
    }
}
