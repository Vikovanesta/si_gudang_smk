<?php

namespace Database\Seeders;

use App\Models\ItemCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Elektronik',
            'Mekanik',
            'Pertukangan',
            'Komputer',
            'Otomotif',
            'Jaringan',
            'laboratorium',
            'Multimedia',
        ];

        foreach ($categories as $category) {
            ItemCategory::create([
                'name' => $category,
            ]);
        }
    }
}
