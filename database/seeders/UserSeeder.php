<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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
        User::firstOrCreate(
            ['email' => 'siti@gmail.com'],
            [
                'name' => 'Siti Binti Dol',
                'password' => Hash::make('password'),
                'gender' => 'female',
                'phone_no' => '0112345678',
            ]
        );
        $this->command->info('Regular user "Siti Binti Dol" created!');

        User::firstOrCreate(
            ['email' => 'rahman@gmail.com'],
            [
                'name' => 'Rahman Bin Kassim',
                'password' => Hash::make('password'),
                'gender' => 'male',
                'phone_no' => '0198765432',
            ]
        );
        $this->command->info('Regular user "Rahman Bin Kassim" created!');

        User::firstOrCreate(
            ['email' => 'aishah@gmail.com'],
            [
                'name' => 'Aishah Binti Daud',
                'password' => Hash::make('password'),
                'gender' => 'female',
                'phone_no' => '0134567890',
            ]
        );
        $this->command->info('Regular user "Aishah Binti Daud" created!');

        User::firstOrCreate(
            ['email' => 'iman@gmail.com'],
            [
                'name' => 'Iman Binti Ismail',
                'password' => Hash::make('password'),
                'gender' => 'female',
                'phone_no' => '0112345678',
                'is_member' => false,
            ]
        );

        User::firstOrCreate(
            ['email' => 'amin@student.uitm.edu.my'],
            [
                'name' => 'Amin Bin Ahmad',
                'password' => Hash::make('password'),
                'gender' => 'male',
                'phone_no' => '0141234567',
                'is_member' => true,
            ]
        );
                $this->command->info('Users seeded: 1 normal + 1 UiTM community.');
    }
}
