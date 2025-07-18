<?php

namespace App\Models\Libraries;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LibLibrarian extends Model
{
    use HasFactory;

    protected $fillable = [
        'library_id',
        'password',
        'user_id'
    ];

    private $newPassword = '';

    public function getEmailAttribute(): string
    {
        return $this->user->email;
    }

    public function make(string $schoolName, int $schoolId, int $libraryId): void
    {
        $user = $this->makeUser($schoolName, $schoolId);

        $this->library_id = $libraryId;
        $this->user_id = $user->id;
        $this->password = $this->newPassword;
        $this->save();
    }

    private function makeUser(string $schoolName, int $schoolId): User
    {
        $this->newPassword = $this->makeLibrarianPassword();

        return User::create(
            [
                'name' => 'Student Librarian',
                'firstName' => 'Student',
                'lastName' => 'Librarian',
                'email' => $this->makeLibrarianEmail($schoolName, $schoolId),
                'pronoun_id' => 1,
                'password' => bcrypt($this->newPassword),
            ]
        );
    }

    /**
     * generates approximately 37.9 billion unique combinations
     * @return string
     * @throws \Random\RandomException
     */
    private function makeLibrarianPassword(): string
    {
        // Define character pools
        $numbers = '23456789'; //no zero
        $uppercase = 'ABCDEFGHIJKLMNPQRSTUVWXYZ'; //no capital "O"
        $specials = '!@#$%^&*()=+[]{}<>?'; //no .,-_

        // Pick 6 random numeric characters
        $numChars = [];
        for ($i = 0; $i < 6; $i++) {
            $numChars[] = $numbers[random_int(0, strlen($numbers) - 1)];
        }

        // Pick 1 random uppercase character
        $upperChar = $uppercase[random_int(0, strlen($uppercase) - 1)];

        // Pick 1 random special character
        $specialChar = $specials[random_int(0, strlen($specials) - 1)];

        // Combine all characters
        $passwordChars = array_merge($numChars, [$upperChar, $specialChar]);

        // Shuffle to randomize order
        shuffle($passwordChars);

        // Return as string
        return implode('', $passwordChars);
    }

    private function makeLibrarianEmail(string $schoolName, int $schoolId): string
    {
        // Split the name into parts, filter out empty parts (in case of extra spaces)
        $parts = array_filter(explode(' ', $schoolName), fn($part) => $part !== '');

        // Get the first letter of each part safely
        $initials = array_map(fn($part) => mb_substr($part, 0, 1), $parts);

        // Join initials and convert to lowercase
        $domain = strtolower(implode('', $initials));

        return $schoolId.'@'.$domain.'.library';
    }

    public function regeneratePassword(): void
    {
        $this->password = $this->makeLibrarianPassword();
        $this->save();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
