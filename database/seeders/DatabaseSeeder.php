<?php

namespace Database\Seeders;

use App\Models\Phone;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $permissions = [];
        $permissions[] = Permission::firstOrCreate(['name' => 'دسترسی کاربران'],['name' => 'دسترسی کاربران', 'guard_name' => 'api']);
        $permissions[] = Permission::firstOrCreate(['name' => 'دسترسی نقش ها'],['name' => 'دسترسی نقش ها', 'guard_name' => 'api']);

        $user = User::query()->firstOrCreate(['national_code' => '1234567890'],[
            'first_name' => 'مدیریت',
            'last_name' => 'admin',
            'national_code' => '1234567890',
            'password' => Hash::make(Config::get('added.adminPassword'))
        ]);
        $phone = Phone::query()->firstOrCreate(['user_id' => $user->id],[
           'user_id' => $user->id,
           'number' =>  Config::get('added.adminPhone')
        ]);

        $role = Role::firstOrCreate(['name' => 'مدیریت'],['name' => 'مدیریت', 'guard_name' => 'api']);
        $role->syncPermissions($permissions);

        $user->assignRole('مدیریت');

        $testUser = User::query()->firstOrCreate(['national_code' => '01234567890'],[
            'first_name' => 'تست',
            'last_name' => 'test',
            'national_code' => '01234567890',
            'password' => Hash::make('12345678')
        ]);
        Phone::query()->firstOrCreate(['user_id' => $testUser->id],[
            'user_id' => $testUser->id,
            'number' =>  '09911231234'
        ]);

        $clientExists = DB::table('oauth_clients')
            ->where('personal_access_client', true)
            ->exists();

        if (!$clientExists) {
            $client = new ClientRepository();
            $personalClient = $client->createPersonalAccessClient(
                null,
                'Personal Access Client',
                config('app.url')
            );
        }
    }
}
