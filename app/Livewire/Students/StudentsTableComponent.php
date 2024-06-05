<?php

namespace App\Livewire\Students;

use App\Livewire\BasePage;
use App\Models\Students\Student;
use Carbon\Carbon;
use Livewire\Component;

class StudentsTableComponent extends BasePage
{
    public function mount(): void
    {
        parent::mount();
        $this->hasFilters = true;
        $this->hasSearch = true;
    }

    public function render()
    {
        return view('livewire..students.students-table-component',
            [
                'columnHeaders' => $this->getColumnHeaders(),
                'rows' => $this->getRows(),
            ]);
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function getColumnHeaders(): array
    {
        return [
            'name',
            'class of',
            'voice part',
            'height',
            'birthday',
            'shirt size',
        ];
    }

    private function getHeightVo(int $inches): string
    {
        return $inches.' ('.floor($inches / 12)."' ".($inches % 12).'")';
    }

    private function getRows(): array
    {
        $a = [];

        return Student::query()
            ->join('school_student', 'students.id', '=', 'school_student.student_id')
            ->join('schools', 'school_student.school_id', '=', 'schools.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('voice_parts', 'students.voice_part_id', '=', 'voice_parts.id')
            ->join('phone_numbers AS mobile', function ($join) {
                $join->on('users.id', '=', 'mobile.user_id')
                    ->where('mobile.phone_type', '=', 'mobile');
            })
            ->join('phone_numbers AS home', function ($join) {
                $join->on('users.id', '=', 'home.user_id')
                    ->where('home.phone_type', '=', 'home');
            })
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->orderBy('users.middle_name')
            ->select('users.name', 'schools.name AS schoolName', 'students.class_of AS classOf',
                'students.height', 'students.birthday', 'students.shirt_size AS shirtSize', 'students.id AS studentId',
                'voice_parts.descr AS voicePart', 'users.email', 'mobile.phone_number AS phoneMobile',
                'home.phone_number AS phoneHome')
            ->get()
            ->toArray();

    }
}
