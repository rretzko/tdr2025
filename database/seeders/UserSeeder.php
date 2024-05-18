<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ViewCard;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private array $seeds;

    public function __construct()
    {
        $this->seeds = $this->buildSeeds();
    }

    private function buildSeeds(): array
    {
        return [
            [
                'id' => 368,
                'name' => 'Rick Retzko',
                'email' => 'rick@mfrholdings.com',
                'email_verified_at' => Carbon::now(),
                'password' => bcrypt('45 Dayton Crescent*')
            ]
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->seeds as $seed) {

            User::create(
                [
                    'id' => $seed['id'],
                    'name' => $seed['name'],
                    'email' => $seed['email'],
                    'email_verified_at' => $seed['email_verified_at'],
                    'password' => $seed['password'],
                ]
            );
        }
    }
}
