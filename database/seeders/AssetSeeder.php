<?php

namespace Database\Seeders;

use App\Models\County;
use App\Models\Ensembles\Asset;
use App\Models\Schools\School;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    private array $seeds;

    public function __construct()
    {
        $this->seeds = $this->buildSeeds();
    }

    private function buildSeeds(): array
    {
        return [
            'cummerbund',
            'folder',
            'gloves',
            'gown',
            'necklace',
            'sash',
            'tuxedo'
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->seeds as $seed) {

            Asset::create(
                [
                    'name' => $seed,
                ]
            );
        }

    }
}
