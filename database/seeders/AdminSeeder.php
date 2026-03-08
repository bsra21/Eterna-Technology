<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        /*   User::create([
            'first_name' => 'Admin',
            'last_name' => 'System',
            'email' => 'admin@admin.com',
            'phone' => '0000000000',
            'password' => Hash::make('123456'),
            'role' => 'admin'
        ]);*/
    }
}
