<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

readonly class UserNameService
{
    public function persistNameParts(User $user): void
    {
        $nameParts = $this->splitName($user->name);

        $user->update(
            [
                'prefix_name' => $nameParts['prefix_name'] ?? null,
                'first_name' => $nameParts['first_name'] ?? null,
                'middle_name' => $nameParts['middle_name'] ?? null,
                'last_name' => $nameParts['last_name'] ?? null,
                'suffix_name' => $nameParts['suffix'] ?? null,
            ]
        );


    }

    private function splitName($fullName): array
    {
        $parts = explode(' ', $fullName);

        return [
            'prefix_name' => $parts[0] ?? null,
            'first_name' => $parts[1] ?? null,
            'middle_name' => $parts[2] ?? null,
            'last_name' => $parts[3] ?? null,
            'suffix' => $parts[4] ?? null,
        ];
    }
}
