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
                'name' => 'Material A',
            ],
            [
                'material_category_id' => 1,
                'name' => 'Material B',
            ],
            [
                'material_category_id' => 2,
                'name' => 'Material C',
            ],
            [
                'material_category_id' => 2,
                'name' => 'Material D',
            ],
            [
                'material_category_id' => 3,
                'name' => 'Material E',
            ],
            [
                'material_category_id' => 3,
                'name' => 'Material F',
            ],
            [
                'material_category_id' => 4,
                'name' => 'Material G',
            ],
            [
                'material_category_id' => 4,
                'name' => 'Material H',
            ],
        ];

        foreach ($materials as $material) {
            Material::create($material);
        }
    }
}
