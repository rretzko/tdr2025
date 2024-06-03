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
            ['descant', 'des', 1],
            ['soprano', 'sop', 2],
            ['soprano i', 'si', 3],
            ['soprano ii', 'sii', 4],
            ['alto', 'alt', 5],
            ['alto i', 'ai', 6],
            ['alto ii', 'aii', 7],
            ['tenor', 'ten', 8],
            ['tenor i', 'ti', 9],
            ['tenor ii', 'tii', 10],
            ['baritone', 'bar', 11],
            ['high baritone', 'hb', 12],
            ['low baritone', 'lb', 13],
            ['bass baritone', 'bb', 14],
            ['bass', 'bass', 15],
            ['bass i', 'bi', 16],
            ['bass ii', 'bii', 17],
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
