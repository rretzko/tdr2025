<?php

namespace App\Livewire\Ensembles\Members;

use App\Models\Ensembles\Ensemble;
use App\Models\Ensembles\Members\Member;
use App\Models\Pronoun;
use App\Models\Students\Student;
use App\Models\UserConfig;
use App\Services\CalcClassOfFromGradeService;
use App\Services\CalcSeniorYearService;
use Illuminate\Support\Facades\DB;

class MemberCreateComponent extends BasePageMember
{
    public string $resultsName = '';

    public function mount(): void
    {
        parent::mount();

        if (count($this->schools) === 1) {

            $this->form->schoolId = array_key_first($this->schools);
            $this->form->schoolName = $this->schools[$this->form->schoolId];
            $service = new CalcSeniorYearService();
            $this->form->schoolYear = $service->getSeniorYear();
        }

        if (count($this->filters->ensembles()) === 1) {

            $this->form->ensembleId = array_key_first($this->filters->ensembles());
            $this->form->ensembleName = Ensemble::find($this->form->ensembleId)->name;
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
                'nonmembers' => $this->getNonmembers(),
            ]);
    }

    public function save()
    {
        $this->form->update();

        return redirect()->route('members');
    }

    /**
     * Add the submitted member and then stay on the Create Member form, persisting appropriate values
     * @return void
     */
    public function saveAndStay()
    {
        $this->form->updateAndStay();

        return redirect()->back();
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
            $classOfs = $this->getEnsembleClassOfs();
            $members = $this->getCurrentMembers();

            $names = Student::query()
                ->join('users', 'users.id', '=', 'students.user_id')
                ->join('school_student', 'school_student.student_id', '=', 'students.id')
                ->leftJoin('ensemble_members', 'students.id', '=', 'ensemble_members.student_id')
                ->where('users.name', 'LIKE', '%'.$this->form->name.'%')
                ->where('school_student.school_id', $this->form->schoolId)
                ->whereIn('students.class_of', $classOfs)
                ->whereNotIn('students.id', $members)
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
            } else {
                $str = '<ul><li class="text-red-500">No non-members found like "' . $this->form->name . '".</li></ul>';
            }
        }

        $this->resultsName = $str;
    }

    private function getCurrentMembers(): array
    {
        return Member::query()
            ->where('ensemble_id', $this->form->ensembleId)
            ->where('school_year', $this->form->schoolYear)
            ->where('school_id', $this->form->schoolId)
            ->pluck('student_id')
            ->toArray();
    }

    private function getEnsembleClassOfs(): array
    {
        $ensemble = Ensemble::find($this->form->ensembleId);
        return $ensemble->classOfsArray($this->form->schoolYear);
    }

    private function getNonmembers(): array
    {
        $schoolId = UserConfig::getValue('schoolId');
        $ensemble = Ensemble::find($this->form->ensembleId);
        $classOfs = $ensemble->classOfsArray($this->form->srYear);

        return DB::table('school_student')
            ->join('students', 'school_student.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('school_id', $schoolId)
            ->whereIn('students.class_of', $classOfs)
            ->select('school_student.student_id AS studentId',
                'users.name AS name',
                DB::raw('CONCAT(users.last_name, ",",users.first_name," ",users.middle_name) AS alphaName')
            )
            ->orderBy('alphaName')
            ->get()
            ->toArray();
    }
}
