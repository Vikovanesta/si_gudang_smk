<?php

namespace Database\Seeders;

use App\Models\Borrowing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BorrowingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Borrowing::create([
            'request_id' => 1,
            'status_id' => 3, // returned
            'borrowed_at' => '2021-08-01 08:23:12',
            'returned_at' => '2021-08-03 16:45:58',
        ]);
    }
}
