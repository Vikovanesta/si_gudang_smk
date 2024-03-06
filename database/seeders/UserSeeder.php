<?php

namespace Database\Seeders;

use App\Models\Laboran;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
                'password' => 'password',
                'phone' => '081234567890',
            ],
            [
                'role_id' => 2,
                'email' => 'student@mail.com',
                'password' => 'password',
                'phone' => '081234567891',
            ],
            [
                'role_id' => 3,
                'email' => 'teacher@mail.com',
                'password' => 'password',
                'phone' => '081234567892',
            ],
            [
                'role_id' => 4,
                'email' => 'laboran@mail.com',
                'password' => 'password',
                'phone' => '081234567893',
            ],
        ];

        foreach ($users as $user) {
            \App\Models\User::create($user);
        }

        Student::create([
            'user_id' => 2,
            'class_id' => 1,
            'name' => 'Budi',
            'nisn' => '1234567890',
            'year_in' => '2021',
            'date_of_birth' => '2000-01-01',
        ]);
        
        Teacher::create([
            'user_id' => 3,
            'name' => 'Susi',
            'nip' => '1234567890',
        ])->subjects()->attach([1, 2]);

        Laboran::create([
            'user_id' => 4,
            'name' => 'Joko',
            'nip' => '1234567899',
        ]);
    }
}
