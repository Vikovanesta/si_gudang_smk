<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Borrowing;
use App\Models\Item;
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
            WarehouseSeeder::class,
            MaterialSeeder::class,
            ItemSeeder::class,

            BorrowingStatusSeeder::class,
            RequestStatusSeeder::class,
            RequestSeeder::class,
            RequestDetailSeeder::class,
            BorrowedItemSeeder::class,
            BorrowingSeeder::class,
        ]);
    }
}
