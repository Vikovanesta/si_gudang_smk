<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materials = [
            [
                'material_category_id' => 1,
                'name' => 'Bahan Kimia A',
            ],
            [
                'material_category_id' => 1,
                'name' => 'Bahan Kimia B',
            ],
            [
                'material_category_id' => 2,
                'name' => 'Bahan Logam A',
            ],
            [
                'material_category_id' => 2,
                'name' => 'Bahan Logam B',
            ],
            [
                'material_category_id' => 3,
                'name' => 'Bahan Plastik A',
            ],
            [
                'material_category_id' => 3,
                'name' => 'Bahan Plastik B',
            ],
            [
                'material_category_id' => 4,
                'name' => 'Bahan Kain A',
            ],
            [
                'material_category_id' => 4,
                'name' => 'Bahan Kain B',
            ],
            [
                'material_category_id' => 5,
                'name' => 'Bahan Kayu A',
            ],
            [
                'material_category_id' => 5,
                'name' => 'Bahan Kayu B',
            ],
        ];

        foreach ($materials as $material) {
            Material::create($material);
        }
    }
}
