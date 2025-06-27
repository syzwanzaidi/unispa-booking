<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        $this->call([
            PackageSeeder::class,
            UserSeeder::class,
            AdminSeeder::class,
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
