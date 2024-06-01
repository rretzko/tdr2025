<?php

namespace Database\Seeders;

use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PageInstructionsSeeder::class);
        $this->call(PronounSeeder::class);
        $this->call(ViewPageSeeder::class);
        $this->call(ViewCardSeeder::class);
        $this->call(GeostateSeeder::class);
        $this->call(CountySeeder::class);
        $this->call(SchoolSeeder::class);
        $this->call(UserSeeder::class);

    }
}
