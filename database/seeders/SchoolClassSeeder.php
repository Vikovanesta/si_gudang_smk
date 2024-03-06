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
            'X RPL 1',
            'X RPL 2',
            'XI RPL 1',
            'XI RPL 2',
            'XII RPL 1',
            'XII RPL 2',
        ];

        foreach ($classes as $class) {
            SchoolClass::create([
                'name' => $class,
            ]);
        }
    }
}
