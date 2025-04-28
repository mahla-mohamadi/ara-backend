<?php

namespace Database\Seeders;

use App\Models\Phone;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
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
        $permissions[] = Permission::create(['name' => 'دسترسی کاربران', 'guard_name' => 'api']);
        $permissions[] = Permission::create(['name' => 'دسترسی نقش ها', 'guard_name' => 'api']);

        $user = User::query()->create([
            'first_name' => 'مدیریت',
            'last_name' => 'admin',
            'password' => Hash::make(Config::get('added.adminPassword'))
        ]);
        $phone = Phone::query()->create([
           'user_id' => $user->id,
           'number' =>  Config::get('added.adminPhone')
        ]);

        $role = Role::create(['name' => 'مدیریت', 'guard_name' => 'api']);
        $role->syncPermissions($permissions);

        $user->assignRole('مدیریت');

        $testUser = User::query()->create([
            'first_name' => 'تست',
            'last_name' => 'test',
            'password' => Hash::make('12345678')
        ]);
        Phone::query()->create([
            'user_id' => $testUser->id,
            'number' =>  '09911231234'
        ]);
    }
}
