<?php

namespace Database\Seeders;

use App\Models\Pronoun;
use Illuminate\Database\Seeder;

class PronounSeeder extends Seeder
{
    private array $seeds;

    public function __construct()
    {
        $this->seeds = $this->buildSeeds();
    }

    private function buildSeeds(): array
    {
        return [
            ['she/her/hers/herself', 'herself', 'she', 'her', 'her', 1],
            ['he/him/his/himself', 'himself', 'he', 'his', 'him', 2],
            ['(f)ae/(f)aer/(f)aers/(f)aerself', '(f)aerself', '(f)ae', '(f)aers', '(f)aers', 3],
            ['e/ey/em/eir/eirs/eirself', 'eirself', 'e', 'eirs', 'eirs', 4],
            ['per/pers/perself', 'perself', 'per', 'pers', 'pers', 5],
            ['they/them/their/theirs/themself', 'themself', 'they', 'theirs', 'theirs', 6],
            ['ve/ver/vis/verself', 'verself', 've', 'vis', 'vis', 7],
            ['xe/xem/xyr/xyrs/xemself', 'xemself', 'xem', 'xyrs', 'xyrs', 8],
            ['ze,zie,hir/hirs/hirself', 'hirself', 'ze', 'hirs', 'hirs', 9],
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->seeds as $seed) {

            Pronoun::create(
                [
                    'descr' => $seed[0],
                    'intensive' => $seed[1],
                    'personal' => $seed[2],
                    'possessive' => $seed[3],
                    'object' => $seed[4],
                    'order_by' => $seed[5],
                ]
            );
        }

    }
}
