<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Emna Hariz',
            'immat' => '2200067',
            'email' => "emna@chelota.com",
            'phoneNumber' => "23670708",
            'password' => Hash::make("123456789"),
            'function' => 'Responsable RH',
            'status' => 1,
            'access' => 1,
            'image' => 'default.jpg'
        ]);
        DB::table('users')->insert([
            'name' => 'Nour Derbel',
            'immat' => '2200087',
            'email' => "nour@chelota.com",
            'phoneNumber' => "22000000",
            'password' => Hash::make("123456789"),
            'function' => 'Responsable RH',
            'status' => 1,
            'access' => 1,
            'image' => 'default.jpg'
        ]);
        DB::table('users')->insert([
            'name' => 'Ayoub Dammak',
            'immat' => '22000650',
            'email' => "ayoub@chelota.com",
            'phoneNumber' => "25000000",
            'password' => Hash::make("123456789"),
            'function' => 'Responsable RH',
            'status' => 1,
            'access' => 1,
            'image' => 'default.jpg'
        ]);     DB::table('users')->insert([
            'name' => 'Marwa Hariz',
            'immat' => '2200096',
            'email' => "marwa@chelota.com",
            'phoneNumber' => "26000000",
            'password' => Hash::make("123456789"),
            'function' => 'Responsable RH',
            'status' => 1,
            'access' => 1,
            'image' => 'default.jpg'
        ]);

       
    }
}
