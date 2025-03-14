<?php

namespace Database\Seeders;

use App\Models\County;
use App\Models\Geostate;
use App\Models\Schools\School;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GeostateSeeder extends Seeder
{
    private array $seeds;

    public function __construct()
    {
        $this->seeds = $this->buildSeeds();
    }

    private function buildSeeds(): array
    {
        return [
            [1, 'US', 'Alaska', 'AK'],
            [2, 'US', 'Alabama', 'AL'],
            [3, 'US', 'Arkansas', 'AR'],
            [4, 'US', 'American Samoa', 'AS'],
            [5, 'US', 'Arizona', 'AZ'],
            [6, 'US', 'California', 'CA'],
            [7, 'US', 'Colorado', 'CO'],
            [8, 'US', 'Connecticut', 'CT'],
            [9, 'US', 'District of Columbia', 'DC'],
            [10, 'US', 'Delaware', 'DE'],
            [11, 'US', 'Florida', 'FL'],
            [12, 'US', 'Federated States of Micronesia', 'FM'],
            [13, 'US', 'Georgia', 'GA'],
            [14, 'US', 'Guam', 'GU'],
            [15, 'US', 'Hawaii', 'HI'],
            [16, 'US', 'Iowa', 'IA'],
            [17, 'US', 'Idaho', 'ID'],
            [18, 'US', 'Illinois', 'IL'],
            [19, 'US', 'Indiana', 'IN'],
            [20, 'US', 'Kansas', 'KS'],
            [21, 'US', 'Kentucky', 'KY'],
            [22, 'US', 'Louisiana', 'LA'],
            [23, 'US', 'Massachusetts', 'MA'],
            [24, 'US', 'Maryland', 'MD'],
            [25, 'US', 'Maine', 'ME'],
            [26, 'US', 'Marshall Islands', 'MH'],
            [27, 'US', 'Michigan', 'MI'],
            [28, 'US', 'Minnesota', 'MN'],
            [29, 'US', 'Missouri', 'MO'],
            [30, 'US', 'Northern Mariana Islands', 'MP'],
            [31, 'US', 'Mississippi', 'MS'],
            [32, 'US', 'Montana', 'MT'],
            [33, 'US', 'North Carolina', 'NC'],
            [34, 'US', 'North Dakota', 'ND'],
            [35, 'US', 'Nebraska', 'NE'],
            [36, 'US', 'New Hampshire', 'NH'],
            [37, 'US', 'New Jersey', 'NJ'],
            [38, 'US', 'New Mexico', 'NM'],
            [39, 'US', 'Nevada', 'NV'],
            [40, 'US', 'New York', 'NY'],
            [41, 'US', 'Ohio', 'OH'],
            [42, 'US', 'Oklahoma', 'OK'],
            [43, 'US', 'Oregon', 'OR'],
            [44, 'US', 'Pennsylvania', 'PA'],
            [45, 'US', 'Puerto Rico', 'PR'],
            [46, 'US', 'Palau', 'PW'],
            [47, 'US', 'Rhode Island', 'RI'],
            [48, 'US', 'South Carolina', 'SC'],
            [49, 'US', 'South Dakota', 'SD'],
            [50, 'US', 'Tennessee', 'TN'],
            [51, 'US', 'Texas', 'TX'],
            [52, 'US', 'Utah', 'UT'],
            [53, 'US', 'Virginia', 'VA'],
            [54, 'US', 'Virgin Islands', 'VI'],
            [55, 'US', 'Vermont', 'VT'],
            [56, 'US', 'Washington', 'WA'],
            [57, 'US', 'Wisconsin', 'WI'],
            [58, 'US', 'West Virginia', 'WV'],
            [59, 'US', 'Wyoming', 'WY'],
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->seeds as $seed) {

            Geostate::create(
                [
                    'id' => $seed[0],
                    'country_abbr' => $seed[1],
                    'name' => $seed[2],
                    'abbr' => $seed[3],
                ]
            );
        }

    }
}
