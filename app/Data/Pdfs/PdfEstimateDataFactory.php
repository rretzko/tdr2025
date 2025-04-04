<?php

namespace App\Data\Pdfs;

use App\Models\Epayment;
use App\Models\Events\Event;
use App\Models\Events\Versions\CoregistrationManagerMailingAddress;
use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Participations\Registrant;
use App\Models\Events\Versions\Version;
use App\Models\Events\Versions\VersionConfigDate;
use App\Models\Events\Versions\VersionCountyAssignment;
use App\Models\Events\Versions\VersionParticipant;
use App\Models\Pronoun;
use App\Models\Schools\School;
use App\Models\Schools\Teacher;
use App\Models\Students\Student;
use App\Models\Students\VoicePart;
use App\Models\User;
use App\Models\UserConfig;
use App\Services\CalcGradeFromClassOfService;
use App\Services\ConvertToUsdService;
use App\Services\EventEnsemblesVoicePartsArrayService;
use Illuminate\Support\Carbon;

class PdfEstimateDataFactory
{
    private array $dto = [];
    private Event $event;
    private Registrant $registrant;
    private School $school;
//    private Student $student;
    private Teacher $teacher;

//    private User $user;

    public function __construct(private readonly Version $version)
    {
        $this->school = School::find(UserConfig::getValue('schoolId'));
        $this->registrant = new Registrant($this->school->id, $this->version->id);

//        $this->student = Student::find($this->candidate->student_id);
        $this->teacher = Teacher::where('user_id', auth()->id())->first();
//        $this->user = User::find($this->student->user_id);
        $this->event = Event::find($this->version->event_id);

        $this->init();
    }

    private function init()
    {
//        $this->dto['auditionFee'] = $this->getAuditionFee();
//        $this->dto['auditionPeriod'] = $this->getAuditionPeriod();
//        $this->dto['candidateVoicePartDescr'] = $this->getCandidateVoicePartDescr();
//        $this->dto['emergencyContactName'] = $this->getEmergencyContact('name');
//        $this->dto['emergencyContactMobile'] = $this->getEmergencyContact('phone_mobile');
//        $this->dto['ensembleNames'] = $this->getEnsembleNames();
        $this->dto['ePaymentsAllowed'] = $this->version->epayment_teacher;
        $this->dto['ePaymentsUsd'] = $this->getEpayments();
//        $this->dto['first'] = $this->user->first_name;
//        $this->dto['fullName'] = $this->candidate->program_name;
//        $this->dto['fullNameAlpha'] = $this->user->fullNameAlpha;
//        $this->dto['grade'] = $this->getGrade();
        $this->dto['logo'] = $this->getLogo();
//        $this->dto['logoPdf'] = $this->getLogo();
        $this->dto['maxCount'] = $this->event->max_registrant_count;
        $this->dto['organization'] = $this->event->organization;
//        $this->dto['postmarkDeadline'] = $this->getPostmarkDeadline();
//        $this->dto['pronounObject'] = $this->getPronoun('object');
//        $this->dto['pronounPossessive'] = $this->getPronoun('possessive');
        $this->dto['registrants'] = $this->getRegistrants();
        $this->dto['registrationFee'] = $this->getRegistrationFee();
        $this->dto['schoolName'] = $this->school->name;
        $this->dto['schoolShortName'] = $this->school->shortName;
        $this->dto['seniorClassOf'] = $this->version->senior_class_of;
        $this->dto['teacherFullName'] = $this->teacher->user->name;
        $this->dto['totalDue'] = $this->getTotalDue();
        $this->dto['totalRegistrantCount'] = $this->getTotalRegistrantCount();
        $this->dto['versionName'] = $this->version->name;
        $this->dto['versionShortName'] = $this->version->short_name;
        $this->dto['voiceParts'] = $this->getVoiceParts();
        $this->dto['coregistrationManagerAddressArray'] = $this->getCoregistrationManagerAddressArray();
    }

    private function getCoregistrationManagerAddressArray(): array
    {
        $schoolName = $this->school->name;
        $countyId = $this->school->county_id;
        $versionId = $this->version->id;
        $versionParticipantId = VersionCountyAssignment::query()
            ->where('county_id', $countyId)
            ->where('version_id', $versionId)
            ->value('version_participant_id');
        $userId = VersionParticipant::find($versionParticipantId)->user_id;
        $user = User::find($userId);
        $mailingAddressCsv = CoregistrationManagerMailingAddress::query()
            ->where('version_participant_id', $versionParticipantId)
            ->where('version_id', $versionId)
            ->value('mailing_address');

        return array_merge([$user->name], explode(',', $mailingAddressCsv));
    }

//    private function getAuditionFee(): string
//    {
//        $fee = $this->version->fee_registration;
//
//        return ConvertToUsdService::penniesToUsd($fee);
//    }
//
//    private function getAuditionPeriod(): string
//    {
//        $vcds = VersionConfigDate::where('version_id', $this->version->id)->get();
//
//        $openDate = $vcds->where('date_type', 'adjudication_open')
//            ->first()
//            ->version_date;
//
//        $openMd = ($openDate)
//            ? Carbon::parse($openDate)->format('F j')
//            : '*** Open Date Not Found ***';
//
//        $closeDate = $vcds->where('date_type', 'adjudication_close')
//            ->first()
//            ->version_date;
//
//        $closeMd = ($closeDate)
//            ? Carbon::parse($closeDate)->format('F j')
//            : '*** Close Date Not Found ***';
//
//        return $openMd.' - '.$closeMd;
//    }
//
//    private function getCandidateVoicePartDescr(): string
//    {
//        return VoicePart::find($this->candidate->voice_part_id)
//            ->descr;
//    }
//
//    private function getEmergencyContact(string $property): string
//    {
//        $emergencyContacts = $this->student->emergencyContacts ?? collect();
//
//        if ($emergencyContacts->count()) {
//            return $emergencyContacts->first()->$property;
//        }
//
//        return 'none found';
//    }

    private function getEpayments(): float
    {

        $pennies = Epayment::query()
            ->where('school_id', $this->school->id)
            ->where('version_id', $this->version->id)
            ->sum('amount');

        $usd = ConvertToUsdService::penniesToUsd($pennies);

        /**
         * @todo Trace this to source to format as currency
         */
        return str_replace(',', '', $usd);
    }
//
//    private function getEnsembleNames(): array
//    {
//        return $this->event->eventEnsembles->pluck('name')->toArray();
//    }
//
//    private function getGrade(): string
//    {
//        $classOf = $this->student->class_of;
//
//        $service = new CalcGradeFromClassOfService();
//
//        return $service->getGrade($classOf);
//    }
//
    private function getLogo(): string
    {
        $logos = [
            1 => '',
            9 => 'logos/nj-mea-logo.jpg',
            19 => '',
            25 => '',
        ];


        return $logos[$this->event->id]; //'logos/nj-mea-logo.jpg';
    }
//
//    private function getPostmarkDeadline(): string
//    {
//        $postmarkDeadlineDate = VersionConfigDate::query()
//            ->where('version_id', $this->version->id)
//            ->where('date_type', 'postmark_deadline')
//            ->first();
//
//        return ($postmarkDeadlineDate)
//            ? Carbon::parse($postmarkDeadlineDate->version_date)->format('F j, Y')
//            : '*** Postmark Deadline Not Found ***';
//    }
//
//    private function getPronoun(string $column): string
//    {
//        return Pronoun::find($this->user->pronoun_id)->$column;
//    }
//
    /**
     * @return array
     * @since 12-Nov-24 added multiple schools to accommodate co-teacher situations
     */
    private function getRegistrants(): array
    {
        $registrants = [];
        $teacher = Teacher::where('user_id', auth()->id())->first();

        foreach ($teacher->schools as $school) {
            $registrants = array_merge($registrants, $this->registrant->getRegistrantArrayForEstimateForm($school));
        }

        usort($registrants, function ($a, $b) {
            return strcmp($a->last_name, $b->last_name);
        });

        return $registrants;
    }

    private function getRegistrationFee(): float
    {
        $registrationFee = $this->version->fee_registration;

        return ConvertToUsdService::penniesToUsd($registrationFee);
    }

    private function getTotalDue(): float
    {
        $registrantCount = $this->registrant->getRegistrantCount();
        $registrationFee = ConvertToUsdService::penniesToUsd($this->version->fee_registration);

        return (($registrantCount * $registrationFee) - $this->getEpayments());
    }

    private function getTotalRegistrantCount(): int
    {
        return count($this->getRegistrants());
    }

    private function getVoiceParts(): array
    {
        $eventEnsembles = $this->event->eventEnsembles;

        $service = new EventEnsemblesVoicePartsArrayService($eventEnsembles);

        return $service->getEstimateSummaryArray();
    }

    public function getDto(): array
    {
        return $this->dto;
    }
}
