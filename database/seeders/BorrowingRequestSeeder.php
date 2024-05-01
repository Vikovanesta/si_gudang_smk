<?php

namespace Database\Seeders;

use App\Models\BorrowingRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BorrowingRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BorrowingRequest::create([
            'sender_id' => 2,
            'handler_id' => 4,
            'purpose' => 'buat praktikum pak',
        ]);

        BorrowingRequest::create([
            'sender_id' => 2,
            'handler_id' => 4,
            'purpose' => 'Untuk keperluan penelitian',
        ]);
    }
}
