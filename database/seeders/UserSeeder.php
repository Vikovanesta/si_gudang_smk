<?php

namespace Database\Seeders;

use App\Models\Laboran;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'role_id' => 1,
                'email' => 'admin@mail.com',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
            ],
            [
                'role_id' => 2,
                'email' => 'student@mail.com',
                'password' => Hash::make('password'),
                'phone' => '081234567891',
            ],
            [
                'role_id' => 3,
                'email' => 'teacher@mail.com',
                'password' => Hash::make('password'),
                'phone' => '081234567892',
            ],
            [
                'role_id' => 4,
                'email' => 'laboran@mail.com',
                'password' => Hash::make('password'),
                'phone' => '081234567893',
            ],
            [
                'role_id' => 2,
                'email' => 'usman@mail.com',
                'password' => Hash::make('password'),
                'phone' => '081234567894',
            ],
            [
                'role_id' => 3,
                'email' => 'khalid@mail.com',
                'password' => Hash::make('password'),
                'phone' => '081234567895',
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        Student::create([
            'user_id' => 2,
            'class_id' => 1,
            'name' => 'Ismail Ahmad Kanabawi',
            'nisn' => '1234567890',
            'year_in' => '2021',
            'date_of_birth' => '2000-01-01',
        ]);

        Teacher::create([
            'user_id' => 3,
            'name' => 'Susi Susanti',
            'nip' => '1234567890',
        ])->subjects()->attach([1, 2]);

        Laboran::create([
            'user_id' => 4,
            'name' => 'Khidr Karawita',
            'nip' => '1234567899',
        ]);

        Student::create([
            'user_id' => 5,
            'class_id' => 6,
            'name' => 'Usman Abdul Jalil Sisha',
            'nisn' => '1234567891',
            'year_in' => '2021',
            'date_of_birth' => '2000-01-01',
        ]);

        Teacher::create([
            'user_id' => 6,
            'name' => 'Khalid Kashmiri',
            'nip' => '1234567891',
        ])->subjects()->attach([3, 4, 5]);
    }
}
