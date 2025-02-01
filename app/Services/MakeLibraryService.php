<?php

namespace App\Services;


use App\Models\Libraries\Library;
use App\Models\Schools\Teacher;

class MakeLibraryService
{
    public function __construct()
    {
        $this->makeHomeLibrary();
    }

    private function makeHomeLibrary(): void
    {
        $teacherId = Teacher::where('user_id', auth()->id())->first()->id;
        if (!Library::query()
            ->where('teacher_id', $teacherId)
            ->where('name', 'Home Library')
            ->exists()) {
            Library::create(
                [
                    'school_id' => 0,
                    'teacher_id' => $teacherId,
                    'name' => 'Home Library',
                ]
            );
        }
    }
}

