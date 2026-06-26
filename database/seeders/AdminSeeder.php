<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admins')->insert([
            'nama'           => 'Super Admin',
            'email'          => 'admin@sistemmata.com',
            'password'       => Hash::make('admin123'),
            'is_super_admin' => true,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }
}