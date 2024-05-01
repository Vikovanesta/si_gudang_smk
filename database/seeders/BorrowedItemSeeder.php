<?php

namespace Database\Seeders;

use App\Models\BorrowedItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BorrowedItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BorrowedItem::create([
            'item_id' => 1,
            'request_detail_id' => 1,
            'quantity' => 6,
        ]);

        BorrowedItem::create([
            'item_id' => 2,
            'request_detail_id' => 1,
            'quantity' => 5,
        ]);

        BorrowedItem::create([
            'item_id' => 1,
            'request_detail_id' => 2,
            'quantity' => 6,
        ]);

        BorrowedItem::create([
            'item_id' => 2,
            'request_detail_id' => 3,
            'quantity' => 5,
        ]);

        BorrowedItem::create([
            'item_id' => 3,
            'request_detail_id' => 3,
            'quantity' => 5,
        ]);
    }
}
