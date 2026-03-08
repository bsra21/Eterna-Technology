<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(AdminSeeder::class);
        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'phone' => '5551112233',
            'email' => 'test@example.com',
        ]);
    }
}
