<?php

namespace Database\Seeders;

use App\Models\Schools\School;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
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
                'name' => 'FJR Music Academy',
                'postal_code' => '07924',
                'city' => 'Bernardsville',
                'county_id' => 17,
            ]
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->seeds as $seed) {

            School::create(
                [
                    'name' => $seed['name'],
                    'postal_code' => $seed['postal_code'],
                    'city' => $seed['city'],
                    'county_id' => $seed['county_id'],
                ]
            );
        }

    }
}
