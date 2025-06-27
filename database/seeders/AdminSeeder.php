<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::firstOrCreate(
            ['admin_username' => 'admin'],
            [
                'admin_password' => Hash::make('admin123'),
            ]
        );

        $this->command->info('Admin account "admin" created!');
    }
}
