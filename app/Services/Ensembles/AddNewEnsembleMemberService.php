<?php

namespace App\Services\Ensembles;


class AddNewEnsembleMemberService
{
    public bool $added = false;
    private int $classOf = 0;

    public function __construct(
        private readonly int $schoolId,
        private readonly int $ensembleId,
        private readonly int $schoolYear,
        private readonly string $firstName,
        private readonly string $middleName,
        private readonly string $lastName,
        private readonly string $gradeClassOf,
        private readonly int $voicePartId,
        private readonly string $office = 'member',
        private readonly string $status = 'active'
    ) {
        $this->init();
    }

    private function init(): void
    {
        //calc class from gradeClassOf
        //discover student from schoolId, email, lastName, firstName, middleName, classOf,
        //   if student is found and gradeClassOf > $this->classOf, update student to greater value
        //   if student is not found:
        //      create new user if student is not found
        //      create new student if student is not found
        //      link student to teacher
        //      link student to school
        //update or create new EnsembleMember


    }

}
