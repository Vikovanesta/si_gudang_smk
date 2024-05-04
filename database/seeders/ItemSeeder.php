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
                'name' => 'Item A',
                'stock' => 16,
                'max_stock' => 30,
            ],
            [
                'warehouse_id' => 1,
                'material_id' => 2,
                'name' => 'Item BB',
                'stock' => 22,
                'max_stock' => 25,
            ],
            [
                'warehouse_id' => 2,
                'material_id' => 3,
                'name' => 'Item CCC',
                'stock' => 14,
                'max_stock' => 27,
            ],
            [
                'warehouse_id' => 2,
                'material_id' => 4,
                'name' => 'Item AB',
                'stock' => 60,
                'max_stock' => 80,
            ],
            [
                'warehouse_id' => 3,
                'material_id' => 5,
                'name' => 'Item B',
                'stock' => 56,
                'max_stock' => 94,
            ],
            [
                'warehouse_id' => 3,
                'material_id' => 6,
                'name' => 'Item AA',
                'stock' => 45,
                'max_stock' => 60,
            ],
            [
                'warehouse_id' => 1,
                'material_id' => 7,
                'name' => 'Item C',
                'stock' => 92,
                'max_stock' => 100,
            ],
            [
                'warehouse_id' => 1,
                'material_id' => 8,
                'name' => 'item D',
                'stock' => 88,
                'max_stock' => 100,
            ],
            [
                'warehouse_id' => 2,
                'material_id' => 9,
                'name' => 'Item KA',
                'stock' => 10,
                'max_stock' => 15,
            ],
            [
                'warehouse_id' => 2,
                'material_id' => 10,
                'name' => 'Item KAA',
                'stock' => 34,
                'max_stock' => 47,
            ]
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
