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
                'category_id' => 1,
                'name' => 'Multimeter',
                'stock' => 25,
                'max_stock' => 30,
            ],
            [
                'warehouse_id' => 1,
                'material_id' => 1,
                'category_id' => 1,
                'name' => 'Solder',
                'stock' => 30,
                'max_stock' => 30,
            ],
            [
                'warehouse_id' => 1,
                'material_id' => 2,
                'category_id' => 2,
                'name' => 'Tang',
                'stock' => 30,
                'max_stock' => 40,
            ],
            [
                'warehouse_id' => 1,
                'material_id' => 2,
                'category_id' => 2,
                'name' => 'Obeng',
                'stock' => 38,
                'max_stock' => 50,
            ],
            [
                'warehouse_id' => 1,
                'material_id' => 3,
                'category_id' => 3,
                'name' => 'Gergaji',
                'stock' => 14,
                'max_stock' => 15,
            ],
            [
                'warehouse_id' => 1,
                'material_id' => 3,
                'category_id' => 3,
                'name' => 'Palu',
                'stock' => 25,
                'max_stock' => 25,
            ],
            [
                'warehouse_id' => 2,
                'material_id' => 4,
                'category_id' => 4,
                'name' => 'Proyektor',
                'stock' => 4,
                'max_stock' => 4,
            ],
            [
                'warehouse_id' => 2,
                'material_id' => 4,
                'category_id' => 4,
                'name' => 'Kabel Data',
                'stock' => 25,
                'max_stock' => 50,
            ],
            [
                'warehouse_id' => 1,
                'material_id' => 5,
                'category_id' => 5,
                'name' => 'Kunci Pas',
                'stock' => 27,
                'max_stock' => 30,
            ],
            [
                'warehouse_id' => 1,
                'material_id' => 5,
                'category_id' => 5,
                'name' => 'Dongkrak',
                'stock' => 20,
                'max_stock' => 22,
            ],
            [
                'warehouse_id' => 2,
                'material_id' => 6,
                'category_id' => 6,
                'name' => 'kabel LAN',
                'stock' => 46,
                'max_stock' => 50,
            ],
            [
                'warehouse_id' => 3,
                'material_id' => 6,
                'category_id' => 6,
                'name' => 'Switch',
                'stock' => 8,
                'max_stock' => 10,
            ],
            [
                'warehouse_id' => 3,
                'material_id' => 6,
                'category_id' => 6,
                'name' => 'Konektor RJ45',
                'stock' => 98,
                'max_stock' => 124,
            ],
            [
                'warehouse_id' => 4,
                'material_id' => 1,
                'category_id' => 7,
                'name' => 'Mikroskop',
                'stock' => 10,
                'max_stock' => 13,
            ],
            [
                'warehouse_id' => 4,
                'material_id' => 1,
                'category_id' => 7,
                'name' => 'Tabung Reaksi',
                'stock' => 28,
                'max_stock' => 32,
            ],
            [
                'warehouse_id' => 4,
                'material_id' => 1,
                'category_id' => 7,
                'name' => 'Kaca Preparat',
                'stock' => 70,
                'max_stock' => 80,
            ],
            [
                'warehouse_id' => 5,
                'material_id' => 2,
                'category_id' => 8,
                'name' => 'kamera DSLR',
                'stock' => 4,
                'max_stock' => 4,
            ],
            [
                'warehouse_id' => 5,
                'material_id' => 2,
                'category_id' => 8,
                'name' => 'Tripod',
                'stock' => 5,
                'max_stock' => 6,
            ],
            [
                'warehouse_id' => 5,
                'material_id' => 2,
                'category_id' => 8,
                'name' => 'Kertas Foto',
                'stock' => 250,
                'max_stock' => 250,
            ],

        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
