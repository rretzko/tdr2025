<?php

namespace App\Livewire\Ensembles\Members;

use App\Models\Pronoun;
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
                'pronouns' => Pronoun::orderBy('order_by')->pluck('descr', 'id'),
                'statuses' => self::STATUSES,
            ]);
    }

    public function save()
    {
        $this->form->update();

        return redirect()->route('members');
    }

    public function setStudent(Student $student): void
    {
        $this->form->setStudentAsMember($student);
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

            $str = '<ul style="list-style-type: none;">';

            foreach ($names as $id => $name) {

                $str .= '<li>'
                    .'<button type="button" wire:click="setStudent('.$id.')" class="text-blue-500">'
                    .$name
                    .'</button>'
                    .'</li>';
            }

            $str .= '</ul>';
        }

        $this->resultsName = $str;
    }
}
