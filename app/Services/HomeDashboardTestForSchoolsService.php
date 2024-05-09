<?php

namespace App\Services;

/**
 *  Test to confirm that auth()->user():
 *  - is a teacher
 *  - with schools.
 * if passed, return raw,
 * else remove the final two cards (students and events) from $raw['cards']
 * and return the remaining elements.
 */
class HomeDashboardTestForSchoolsService
{
    private array $dto = [];

    public function __construct(private readonly array $raw)
    {
        $this->dto = $raw;

        $this->init();
    }

    private function init(): void
    {
        if (!($this->isTeacher() || $this->hasSchools())) {

            //unset Students card
            unset($this->dto['cards'][1]);

            //unset Events card
            unset($this->dto['cards'][2]);
        }
    }

    private function isTeacher(): bool
    {
        return false;
    }

    private function hasSchools(): bool
    {
        return false;
    }

    public function getDto(): array
    {
        return $this->dto;
    }
}
