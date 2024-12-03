<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lists = [
            [
                'name'              => 'Sandi Mulyadi',
                'email'             => 'sandimvlyadi@gmail.com',
                'password'          => Hash::make('password'),
                'email_verified_at' => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($lists as $list) {
            User::create($list);
        }
    }
}
