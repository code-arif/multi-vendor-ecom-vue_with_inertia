<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Super Admin',
            'type' => 'superadmin',
            'vendor_id' => null,
            'email' => 'superadmin@example.com',
            'mobile' => '0123456789',
            'password' => Hash::make('1111'),
            'image' => null,
            'address' => 'Super Admin Address',
            'zip' => '12345',
            'status' => 1,
            'confirm' => 'yes',
        ]);

        Admin::create([
            'name' => 'Admin User',
            'type' => 'admin',
            'vendor_id' => null,
            'email' => 'admin@example.com',
            'mobile' => '0987654321',
            'password' => Hash::make('1111'),
            'image' => null,
            'address' => 'Admin Address',
            'zip' => '67890',
            'status' => 1,
            'confirm' => 'yes',
        ]);
    }
}
