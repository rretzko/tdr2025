<?php

namespace Database\Seeders;

use App\Models\Students\EmergencyContactType;
use Illuminate\Database\Seeder;

class EmergencyContactTypeSeeder extends Seeder
{
    private array $seeds;

    public function __construct()
    {
        $this->seeds = $this->buildSeeds();
    }

    private function buildSeeds(): array
    {
        return [
            ['mother', 1, 1],
            ['father', 2, 2],
            ['grandmother', 1, 3],
            ['grandfather', 2, 4],
            ['aunt', 1, 5],
            ['uncle', 2, 6],
            ['guardian-mother', 1, 7],
            ['guardian-father', 2, 8],
            ['step-mother', 1, 9],
            ['step-father', 2, 10],
            ['foster-mother', 1, 11],
            ['foster-father', 2, 12],
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->seeds as $seed) {

            EmergencyContactType::create(
                [
                    'relationship' => $seed[0],
                    'pronoun_id' => $seed[1],
                    'order_by' => $seed[2],
                ]
            );
        }

    }
}
