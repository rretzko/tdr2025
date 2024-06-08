<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PageInstructionsSeeder::class);
        $this->call(PronounSeeder::class);
        $this->call(EmergencyContactTypeSeeder::class);
        $this->call(ViewPageSeeder::class);
        $this->call(ViewCardSeeder::class);
        $this->call(GeostateSeeder::class);
        $this->call(CountySeeder::class);
        $this->call(SchoolSeeder::class);
        $this->call(VoicePartSeeder::class);
        $this->call(UserSeeder::class);

    }
}
