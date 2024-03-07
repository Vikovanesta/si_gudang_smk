<?php

namespace Database\Seeders;

use App\Models\MaterialCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materialCategories = [
            [
                'name' => 'Bahan Kimia',
            ],
            [
                'name' => 'Bahan Logam',
            ],
            [
                'name' => 'Bahan Plastik',
            ],
            [
                'name' => 'Bahan Kain',
            ],
            [
                'name' => 'Bahan Kayu',
            ],
        ];

        foreach ($materialCategories as $materialCategory) {
            MaterialCategory::create($materialCategory);
        }
    }
}
