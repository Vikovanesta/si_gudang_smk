<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SchoolClassSeeder::class,
            SchoolSubjectSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,

            MaterialCategorySeeder::class,
            ItemCategorySeeder::class,
            WarehouseSeeder::class,
            MaterialSeeder::class,
            ItemSeeder::class,

            BorrowingStatusSeeder::class,
            RequestStatusSeeder::class,
            BorrowingRequestSeeder::class,
            RequestDetailSeeder::class,
            BorrowedItemSeeder::class,
        ]);
    }
}
