<?php

namespace App\Livewire\Ensembles\Members;

use App\Models\Pronoun;
use App\Models\Students\Student;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MemberCreateComponent extends BasePageMember
{
    public string $resultsName = '';

    public function mount(): void
    {
        parent::mount();

        if (count($this->schools) === 1) {

            $this->form->schoolId = array_key_first($this->schools);
            $this->form->schoolName = $this->schools[$this->form->schoolId];
        }

        if (count($this->filters->ensembles()) === 1) {

            $this->form->ensembleId = array_key_first($this->getEnsembles());
            $this->form->ensembleName = $this->getEnsembles()[$this->form->ensembleId];
        }
    }

    public function render()
    {
        return view('livewire..ensembles.members.member-create-component',
            [
                'schools' => $this->schools,
                'ensembles' => $this->filters->ensembles(),
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
        $this->reset('resultsName');

        $this->form->setStudentAsMember($student);
    }

    public function updatedFormName(): void
    {
        $str = '';

        if (!strlen($this->form->name)) {

            $this->reset('resultsName');

        } else {

            $names = Student::query()
                ->join('users', 'users.id', '=', 'students.user_id')
                ->join('school_student', 'school_student.student_id', '=', 'students.id')
                ->where('users.name', 'LIKE', '%'.$this->form->name.'%')
                ->select(DB::raw("CONCAT(users.name, ' (', students.class_of, ')') as name_with_class"), 'students.id')
                ->pluck('name_with_class', 'students.id')
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
        }

        $this->resultsName = $str;
    }
}
