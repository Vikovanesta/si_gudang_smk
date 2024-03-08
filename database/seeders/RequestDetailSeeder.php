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
            'start_date' => '2021-08-01',
            'end_date' => '2021-08-04',
        ]);

        RequestDetail::create([
            'request_id' => 1,
            'status_id' => 2,
            'start_date' => '2021-08-01',
            'end_date' => '2021-08-03',
            'note' => 'gaboleh sampe tanggal 4, sampe tanggal 3 boleh. Item kedua kosong',
            'is_revised' => true,
        ]);
    }
}
