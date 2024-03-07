<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'warehouse_id' => 1,
                'material_id' => 1,
                'name' => 'Bahan Kimia A',
                'stock' => 90,
                'max_stock' => 100,
            ],
            [
                'warehouse_id' => 1,
                'material_id' => 2,
                'name' => 'Bahan Kimia B',
                'stock' => 90,
                'max_stock' => 100,
            ],
            [
                'warehouse_id' => 2,
                'material_id' => 3,
                'name' => 'Bahan Logam A',
                'stock' => 90,
                'max_stock' => 100,
            ],
            [
                'warehouse_id' => 2,
                'material_id' => 4,
                'name' => 'Bahan Logam B',
                'stock' => 90,
                'max_stock' => 100,
            ],
            [
                'warehouse_id' => 3,
                'material_id' => 5,
                'name' => 'Bahan Plastik A',
                'stock' => 90,
                'max_stock' => 100,
            ],
            [
                'warehouse_id' => 3,
                'material_id' => 6,
                'name' => 'Bahan Plastik B',
                'stock' => 90,
                'max_stock' => 100,
            ],
            [
                'warehouse_id' => 1,
                'material_id' => 7,
                'name' => 'Bahan Kain A',
                'stock' => 90,
                'max_stock' => 100,
            ],
            [
                'warehouse_id' => 1,
                'material_id' => 8,
                'name' => 'Bahan Kain B',
                'stock' => 90,
                'max_stock' => 100,
            ],
            [
                'warehouse_id' => 2,
                'material_id' => 9,
                'name' => 'Bahan Kayu A',
                'stock' => 90,
                'max_stock' => 100,
            ],
            [
                'warehouse_id' => 2,
                'material_id' => 10,
                'name' => 'Bahan Kayu B',
                'stock' => 90,
                'max_stock' => 100,
            ]
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
