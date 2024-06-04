<?php

namespace Database\Seeders;

use App\Models\Pronoun;
use App\Models\Students\VoicePart;
use Illuminate\Database\Seeder;

class VoicePartSeeder extends Seeder
{
    private array $seeds;

    public function __construct()
    {
        $this->seeds = $this->buildSeeds();
    }

    private function buildSeeds(): array
    {
        return [
            ['Descant', 'Des', 1],
            ['Soprano', 'Sop', 2],
            ['Soprano I', 'SI', 3],
            ['Soprano II', 'SII', 4],
            ['Alto', 'Alt', 5],
            ['Alto I', 'AI', 6],
            ['Alto II', 'AII', 7],
            ['Tenor', 'Ten', 8],
            ['Tenor I', 'TI', 9],
            ['Tenor II', 'TII', 10],
            ['Baritone', 'Bar', 11],
            ['High Baritone', 'HB', 12],
            ['Low Baritone', 'LB', 13],
            ['BassBaritone', 'BB', 14],
            ['Bass', 'Bass', 15],
            ['Bass I', 'BI', 16],
            ['Bass II', 'BII', 17],
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->seeds as $seed) {

            VoicePart::create(
                [
                    'descr' => $seed[0],
                    'abbr' => $seed[1],
                    'order_by' => $seed[2],
                ]
            );
        }

    }
}
