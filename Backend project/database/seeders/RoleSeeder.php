<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Responsable']);
        Role::create(['name' => 'RH']);
        Role::create(['name' => 'GL']);
        Role::create(['name' => 'TL']);
        Role::create(['name' => 'dev']);
    }
}
