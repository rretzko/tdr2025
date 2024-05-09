<?php

namespace App\Data\Cards;

/**
 * Return dashboard card displaying this user's school information
 */
class SchoolsCard
{
    private array $cards = [];

    public function __construct()
    {
        $this->init();
    }

    private function init(): void
    {
        //fetch teacher's school(s)

        //parse school data to be used by card

        //format parsed data into human-readable card
    }

    public function getCards(): array
    {
        return $this->cards;
    }
}
