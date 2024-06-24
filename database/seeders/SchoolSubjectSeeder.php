<?php

namespace Database\Seeders;

use App\Models\SchoolSubject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            'Pemrograman Dasar',
            'Pemrograman Berorientasi Objek',
            'Kimia Dasar',
            'Fisika Dasar',
            'Biologi Dasar',
            'Teknik Pengolahan Citra',
            'Teknik Pengolahan Audio',
            'Teknik Pengukuran dan Instrumentasi',
            'Manajemen Proyek',
        ];

        foreach ($subjects as $subject) {
            SchoolSubject::create([
                'name' => $subject,
            ]);
        }
    }
}
