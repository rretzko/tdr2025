<?php

namespace Database\Seeders;

use App\Models\Events\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
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
                1, 'CJMEA High School Chorus', 'Region II Chorus', 'NJMEA', 0, 35, 2, '9,10,11,12', 'active',
                'cjmeaLogo.png', 0, 0
            ],
            [
                9, 'NJ All-State Chorus', 'NJ A-S Chorus', 'NJMEA', 30, 0, 2, '9,10,11', 'active',
                'njmeaLogo_transparent.png', 0, 0
            ],
            [
                19, 'NJ All-Shore Chorus', 'All-Shore Chorus', 'New Jersey All-Shore Chorus', 30, 0, 2, '9,10,11',
                'active', '', 1, 1
            ],
            [
                25, 'Morris Area Honor Choirs', 'Morris Honor Choirs', 'Morris Area Choral Directors Association', 0, 0,
                2, '6,7,8,9,10,11,12', 'active', '', 0, 0
            ],
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->seeds as $seed) {

            Event::create(
                [
                    'id' => $seed[0],
                    'name' => $seed[1],
                    'short_name' => $seed[2],
                    'organization' => $seed[3],
                    'audition_count' => 1, //default
                    'max_registrant_count' => $seed[4],
                    'max_upper_voice_count' => $seed[5],
                    'ensemble_count' => $seed[6],
                    'frequency' => 'annual', //default
                    'grades' => $seed[7],
                    'status' => $seed[8],
                    'logo_file' => $seed[9],
                    'logo_file_alt' => 'event logo image', //default
                    'required_height' => $seed[10],
                    'required_shirt_size' => $seed[11],
                    'created_by' => 368,
                ]
            );
        }

    }
}
