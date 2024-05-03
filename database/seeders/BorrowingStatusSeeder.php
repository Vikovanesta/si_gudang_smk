<?php

namespace Database\Seeders;

use App\Models\BorrowingStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BorrowingStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $borrowingStatuses = [
            'pending',
            'borrowed',
            'returned',
            'cancelled',
        ];

        foreach ($borrowingStatuses as $status) {
            BorrowingStatus::create(['name' => $status]);
        }
    }
}
