<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [
            [
                'name' => 'Gudang EC-101',
            ],
            [
                'name' => 'Gudang MD-203',
            ],
            [
                'name' => 'Gudang OT-208',
            ],
            [
                'name' => 'Gudang PT-110',
            ],
            [
                'name' => 'Gudang KM-103',
            ],
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::create($warehouse);
        }
    }
}
