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
            'Electronics',
            'Furniture',
            'Stationery',
            'Clothing',
            'Tools',
            'Sporting Goods',
            'Automotive',
        ];

        foreach ($categories as $category) {
            ItemCategory::create([
                'name' => $category,
            ]);
        }
    }
}
