<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => '鈴木一郎',
            'kana' => 'すずきいちろう',
            'zip' => '123-4567',
            'address' => '京都府西京区11111',
            'tel' => '09012345678',
            'email' => 'user1@example.com',
            'password' => Hash::make('password123'),
        ]);
        DB::table('users')->insert([
            'name' => '佐藤二郎',
            'kana' => 'さとうじろう',
            'zip' => '891-2345',
            'address' => '京都府中京区22222',
            'tel' => '09056781234',
            'email' => 'user2@example.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
