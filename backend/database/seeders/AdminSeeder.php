<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admins')->insert([
            'name' => '管理者',
            'kana' => 'かんりしゃ',
            'zip' => '000-0000',
            'address' => '東京都',
            'tel' => '09011111111',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
