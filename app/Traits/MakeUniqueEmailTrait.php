<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Http\Request;

trait MakeUniqueEmailTrait
{
    public static function makeUniqueEmail(
        string $firstName,
        string $lastName,
    ) {
        $domain = '@studentFolder.info';
        $firstInitial = strtolower($firstName[0]);
        $lastName = strtolower($lastName);
        $suffix = 0;

        do {
            $email = $firstInitial.$lastName;
            if ($suffix > 0) {
                $email .= $suffix;
            }
            $email .= $domain;
            $exists = User::query()->where('email', $email)->exists();
            $suffix++;
        } while ($exists);

        return $email;
    }
}
