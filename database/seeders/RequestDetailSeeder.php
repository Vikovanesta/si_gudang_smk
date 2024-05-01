<?php

namespace Database\Seeders;

use App\Models\RequestDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RequestDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RequestDetail::create([
            'request_id' => 1,
            'status_id' => 3,
            'start_date' => '2021-08-01 08:00:00',
            'end_date' => '2021-08-04 17:00:00',
        ]);

        RequestDetail::create([
            'request_id' => 1,
            'status_id' => 2,
            'start_date' => '2021-08-01 08:00:00',
            'end_date' => '2021-08-03 17:00:00',
            'note' => 'gaboleh sampe tanggal 4, sampe tanggal 3 boleh. Item kedua kosong',
            'is_revision' => true,
        ]);

        RequestDetail::create([
            'request_id' => 2,
            'status_id' => 1,
            'start_date' => '2024-08-02 08:00:00',
            'end_date' => '2024-08-08 17:00:00',
        ]);
    }
}
