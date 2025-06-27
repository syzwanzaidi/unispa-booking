<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'alibinabu@gmail.com'],
            [
                'name' => 'Ali bin Abu',
                'password' => Hash::make('12345678'),
                'gender' => 'male',
                'phone_no' => '0123456789',
            ]
        );

        $this->command->info('Regular user "Ali bin Abu" created!');
    }
}
