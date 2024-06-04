<?php

namespace App\Services;

use App\Models\PhoneNumber;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class FindMatchingStudentService
{
    private array $comparator = [];
    private array $matches = [];

    public function __construct(private readonly mixed $data)
    {
        //parse data from incoming data sender
        $this->comparator = $this->parseData();

        //make comparisons
        $emailMatch = $this->emailMatch($this->comparator['email']); //collection
        $nameMatch = $this->nameMatch($this->comparator['first'], $this->comparator['last']); //collection
        $phoneMobileMatch = $this->phoneMobileMatch($this->comparator['phoneMobile']); //collection
        $classOfMatch = $this->classOfMatch($emailMatch, $nameMatch, $phoneMobileMatch, $this->comparator['classOf']);

        $this->buildMatches($classOfMatch);
    }

    private function parseData(): array
    {
        if (is_a($this->data, 'App\Livewire\Forms\StudentForm')) {

            return $this->parseDataStudentForm();

        }

        Log::error('***** incoming mixed for '.__CLASS__.' is a: '.get_class($this->data).' *****');

        return [];
    }

    private function parseDataStudentForm(): array
    {
        $a = [];

        $a['birthday'] = $this->data->birthday;
        $a['classOf'] = $this->data->classOf;
        $a['email'] = $this->data->email;
        $a['first'] = $this->data->first;
        $a['heightInInches'] = $this->data->heightInInches;
        $a['last'] = $this->data->last;
        $a['middle'] = $this->data->middle;
        $a['phoneHome'] = $this->data->phoneHome;
        $a['phoneMobile'] = $this->data->phoneMobile;
        $a['pronounId'] = $this->data->pronounId;
        $a['shirtSize'] = $this->data->shirtSize;
        $a['suffix'] = $this->data->suffix;
        $a['sysId'] = $this->data->sysId;
        $a['voicePartId'] = $this->data->voicePartId;

        return $a;
    }

    private function emailMatch(string $email): Collection|null
    {
        return User::where('email', $email)->get();
    }

    private function nameMatch(string $first, string $last): \Illuminate\Support\Collection|Collection
    {
        $matches = collect();

        $lastMatches = User::where('name', 'LIKE', '%'.$last.'%')->get();

        if ($lastMatches && $lastMatches->count()) {

            $matches = $lastMatches->merge(User::where('name', 'LIKE', '%'.$first.'%')->get());
        }

        return $matches;
    }

    private function phoneMobileMatch(string $phoneMobile): Collection|\Illuminate\Support\Collection
    {
        $service = new FormatPhoneService();
        $phoneNumber = $service->getPhoneNumber($phoneMobile);

        return User::whereHas('phoneNumbers', function ($query) use ($phoneNumber) {
            $query->where('phone_number', $phoneNumber)
                ->where('phone_type', 'mobile');
        })->get();
    }

    private function classOfMatch(
        $emailMatch,
        $nameMatch,
        $phoneMobileMatch,
        int $classOf
    ): \Illuminate\Support\Collection {
        // Merge the two collections
        $mergedMatches = $emailMatch->merge($nameMatch)->merge($phoneMobileMatch);

        // Filter the merged collection
        $found = $mergedMatches->filter(function ($user) use ($classOf) {
            return $user->isStudent() && $user->student->class_of == $classOf;
        });

        return $found->values(); // Reset the keys of the collection
    }

    private function buildMatches($matches): void
    {
        if ($matches->count()) {

            foreach ($matches as $user) {

                $this->matches[] = [
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            }
        }
    }

    public function getMatches(): array
    {
        return $this->matches;
    }
}
