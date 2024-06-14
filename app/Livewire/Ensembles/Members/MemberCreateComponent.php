<?php

namespace App\Livewire\Ensembles\Members;

use App\Models\Students\Student;
use Livewire\Component;

class MemberCreateComponent extends BasePageMember
{
    public string $resultsName = '';

    public function render()
    {
        return view('livewire..ensembles.members.member-create-component',
            [
                'schools' => $this->schools,
                'ensembles' => $this->getEnsembles(),
                'voiceParts' => $this->getVoiceParts(),
                'offices' => self::OFFICES,
                'statuses' => self::STATUSES,
            ]);
    }

    public function save()
    {
        $this->form->update();
    }

    public function updatedFormName(): void
    {
        $str = '';

        $names = Student::query()
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('school_student', 'school_student.student_id', '=', 'students.id')
            ->where('users.name', 'LIKE', '%'.$this->form->name.'%')
//            ->where('school_student.student_id', '=', 'students.id')
            ->pluck('users.name', 'students.id')
            ->toArray();

        if ($names) {

            $str = '<ul>';

            foreach ($names as $id => $name) {

                $str .= '<li>'.$name.'</li>';
            }

            $str .= '</ul>';
        }

        $this->resultsName = $str;
    }
}
