<?php

namespace Database\Seeders;

use App\Models\RequestStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RequestStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requestStatuses = [
            'pending',
            'approved',
            'rejected',
        ];

        foreach ($requestStatuses as $status) {
            RequestStatus::create(['name' => $status]);
        }
    }
}
