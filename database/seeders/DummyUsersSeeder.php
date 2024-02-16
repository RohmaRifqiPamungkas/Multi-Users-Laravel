<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userData = [
            [
                'name' => 'Mas Users',
                'email' => 'userapp@gmail.com',
                'role' => 'user',
                'password' => bcrypt('12345678')
            ],
            [
                'name' => 'Mas Admin',
                'email' => 'adminapp@gmail.com',
                'role' => 'admin',
                'password' => bcrypt('12345678')
            ],
            [
                'name' => 'Mas Operator',
                'email' => 'operatorrapp@gmail.com',
                'role' => 'operator',
                'password' => bcrypt('12345678')
            ],
        ];

        foreach($userData as $key => $val) {
            User::create($val);
        }
    }
}
