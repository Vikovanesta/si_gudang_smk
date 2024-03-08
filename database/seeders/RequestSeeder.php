<?php

namespace Database\Seeders;

use App\Models\Request;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Request::create([
            'sender_id' => 2,
            'handler_id' => 4,
            'purpose' => 'buat praktikum pak',
        ]);
    }
}
