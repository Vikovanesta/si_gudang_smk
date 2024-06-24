<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            'X Teknik Komputer dan Jaringan',
            'X Teknik Otomotif',
            'XI Teknik Elektronika',
            'XI Teknik Konstruksi',
            'XII Multimedia',
            'XII Teknik Kimia Industri',
        ];

        foreach ($classes as $class) {
            SchoolClass::create([
                'name' => $class,
            ]);
        }
    }
}
