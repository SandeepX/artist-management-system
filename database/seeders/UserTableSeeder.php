<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'role' => 'super_admin',
            'phone' => '1234567890',
            'dob' => '1999-01-01',
            'gender' => 'm',
            'address' => 'baneswor, kathmandu',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $existingUser = DB::table('users')->where('first_name', 'admin')->first();

        if ($existingUser) {
            DB::table('users')->where('id', $existingUser->id)->update($data);
        } else {
            DB::table('users')->insert($data);
        }
    }
}
