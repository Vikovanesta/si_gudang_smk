<?php

namespace Database\Seeders;

use App\Models\StudentRegistration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentRegistrations = [
            [
                'name' => 'Timour Tulipov',
                'email' => 'timour@mail.com',
                'phone' => '081234567897',
                'password' => 'password',
                'nisn' => '7463728193',
                'class_id' => 1,
                'year_in' => '2021',
                'date_of_birth' => '2009-01-02',
            ],
            [
                'name' => 'Jafar Bilal Ismail',
                'email' => 'jafar@mail.com',
                'phone' => '081234567896',
                'password' => 'password',
                'nisn' => '7463728192',
                'class_id' => 1,
                'year_in' => '2021',
                'date_of_birth' => '2009-01-01',
            ],
            [
                'name' => 'Zulfikar Hadi Surya',
                'email' => 'zulfikar@mail.com',
                'phone' => '081234567898',
                'password' => 'password',
                'nisn' => '7463728194',
                'class_id' => 2,
                'year_in' => '2021',
                'date_of_birth' => '2009-01-03',
            ],
            [
                'name' => 'Intan Dewi',
                'email' => 'intan@mail.com',
                'phone' => '081234567899',
                'password' => 'password',
                'nisn' => '7463728195',
                'class_id' => 5,
                'year_in' => '2021',
                'date_of_birth' => '2009-01-04',
            ],
            [
                'name' => 'Maria Maryana Borisova',
                'email' => 'maria@mail.com',
                'phone' => '081234567990',
                'password' => 'password',
                'nisn' => '7463728196',
                'class_id' => 3,
                'year_in' => '2021',
                'date_of_birth' => '2009-01-05',
            ],
            [
                'name' => 'Rahmat Hidayat',
                'email' => 'rahmat@mail.com',
                'phone' => '081234567991',
                'password' => 'password',
                'nisn' => '7463728197',
                'class_id' => 4,
                'year_in' => '2021',
                'date_of_birth' => '2009-01-06',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@mail.com',
                'phone' => '081234567992',
                'password' => 'password',
                'nisn' => '7463728198',
                'class_id' => 5,
                'year_in' => '2021',
                'date_of_birth' => '2009-01-07',
            ],
            [
                'name' => 'Star Dragon Iron',
                'email' => 'dragon@mail.com',
                'phone' => '081234567993',
                'password' => 'password',
                'nisn' => '7463728199',
                'class_id' => 6,
                'year_in' => '2021',
                'date_of_birth' => '2009-01-08',
            ],
            [
                'name' => 'hehehe',
                'email' => 'hehe@mali.net',
                'phone' => '081234567994',
                'password' => 'password',
                'nisn' => '7463728100',
                'class_id' => 4,
                'year_in' => '2077',
                'date_of_birth' => '2019-01-09',
            ],
            [
                'name' => 'Samsul L. Jackson',
                'email' => 'samsul@mail.com',
                'phone' => '081234567995',
                'password' => 'password',
                'nisn' => '7463728101',
                'class_id' => 4,
                'year_in' => '2021',
                'date_of_birth' => '2009-01-10',
            ],
            [
                'name' => 'Insulindian Phasmid',
                'email' => 'phasmid@mail.com',
                'phone' => '081234567996',
                'password' => 'password',
                'nisn' => '7463728102',
                'class_id' => 3,
                'year_in' => '2021',
                'date_of_birth' => '2009-01-11',
            ]
        ];

        foreach ($studentRegistrations as $studentRegistration) {
            StudentRegistration::create($studentRegistration);
        }
    }
}
