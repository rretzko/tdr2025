<?php

namespace App\Services;

use App\Models\User;

class JoinNamePartsIntoNameService
{
    private string $name;

    public function __construct(private readonly User $user)
    {
        $this->init();
    }

    private function init(): void
    {
        $nameParts = User::query()
            ->where('id', $this->user->id)
            ->select('prefix_name', 'first_name', 'middle_name', 'last_name', 'suffix_name')
            ->first()
            ->toArray();

        $this->name = $this->buildName($nameParts);
    }

    private function buildName(array $nameParts): string
    {
        $str = '';

        if ($nameParts['prefix_name']) {
            $str = $nameParts['prefix_name'].' ';
        }
        $str .= $nameParts['first_name'];
        if ($nameParts['middle_name']) {
            $str .= ' '.$nameParts['middle_name'];
        }
        $str .= ' '.$nameParts['last_name'];
        if ($nameParts['suffix_name']) {
            $str .= ', '.$nameParts['suffix_name'];
        }

        return $str;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
