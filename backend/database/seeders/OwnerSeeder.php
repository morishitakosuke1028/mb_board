<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('owners')->insert([
            'name' => 'オーナー佐々木',
            'kana' => 'ささき',
            'company_name' => 'テストオーナー株式会社',
            'company_kana' => 'てすとおーなー',
            'contact_zip' => '111-1111',
            'contact_address' => '大阪府大阪市北区',
            'contact_tel' => '09022222222',
            'secret_zip' => '222-2222',
            'secret_address' => '大阪府大阪市西区',
            'secret_tel' => '09033333333',
            'email' => 'owner1@example.com',
            'password' => Hash::make('password123'),
        ]);
        DB::table('owners')->insert([
            'name' => 'オーナー大谷',
            'kana' => 'おおたに',
            'company_name' => 'テストオーナー2株式会社',
            'company_kana' => 'てすとおーなーつー',
            'contact_zip' => '333-3333',
            'contact_address' => '大阪府和泉市',
            'contact_tel' => '09044444444',
            'secret_zip' => '444-4444',
            'secret_address' => '大阪府茨木市',
            'secret_tel' => '09055555555',
            'email' => 'owner2@example.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
