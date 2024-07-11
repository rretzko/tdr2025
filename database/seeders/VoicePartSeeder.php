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
            [71, 'Descant', 'Des', 1],
            [5, 'Soprano', 'Sop', 2],
            [63, 'Soprano I', 'SI', 3],
            [64, 'Soprano II', 'SII', 4],
            [1, 'Alto', 'Alt', 5],
            [65, 'Alto I', 'AI', 6],
            [66, 'Alto II', 'AII', 7],
            [6, 'Tenor', 'Ten', 8],
            [67, 'Tenor I', 'TI', 9],
            [68, 'Tenor II', 'TII', 10],
            [2, 'Baritone', 'Bar', 11],
            [72, 'High Baritone', 'HB', 12],
            [73, 'Low Baritone', 'LB', 13],
            [4, 'Bass Baritone', 'BB', 14],
            [3, 'Bass', 'Bass', 15],
            [69, 'Bass I', 'BI', 16],
            [70, 'Bass II', 'BII', 17],
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
                    'id' => $seed[0],
                    'descr' => $seed[1],
                    'abbr' => $seed[2],
                    'order_by' => $seed[3],
                ]
            );
        }

    }
}
