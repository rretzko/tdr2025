<?php

namespace App\ValueObjects;

use App\Models\Epayment;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Participations\StudentPayment;

class TotalStudentRegistrationPayments
{
    public function getPayment(int $candidateId)
    {
        $candidate = Candidate::find($candidateId);

        $cash = $this->paidToTeacher($candidate);
        $ePayment = $this->ePayment($candidate);

        return ($cash + $ePayment);
    }

    private function paidToTeacher(Candidate $candidate): float
    {
        return StudentPayment::query()
            ->where('candidate_id', $candidate->id)
            ->where('version_id', $candidate->version_id)
            ->where('school_id', $candidate->school_id)
            ->sum('amount');
    }

    private function ePayment(Candidate $candidate): float
    {
        return Epayment::query()
            ->where('candidate_id', $candidate->id)
            ->where('version_id', $candidate->version_id)
            ->where('school_id', $candidate->school_id)
            ->sum('amount');
    }
}
