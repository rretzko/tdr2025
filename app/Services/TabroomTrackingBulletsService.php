<?php

namespace App\Services;

use App\Models\Events\Versions\Participations\Candidate;
use App\Models\Events\Versions\Room;
use App\Models\Events\Versions\Scoring\RoomVoicePart;
use App\Models\Events\Versions\Scoring\ScoreFactor;
use App\Models\Schools\Teacher;
use App\Models\Students\VoicePart;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class TabroomTrackingBulletsService
{
    public int $studentCount = 0;
    private array $candidates = [];


    public function __construct(private int $versionId, private int $roomId = 0)
    {
        $this->init();
    }

    public function getCandidates(): array
    {
        return $this->candidates;
    }

    /** END OF PUBLIC FUNCTIONS **************************************************/

    private function init(): void
    {
        $a = [];
        $rooms = $this->getRooms();

        foreach ($rooms as $room) {
            $roomVoiceParts = RoomVoicePart::where('room_id', $room->id)->get();
            $voicePartIds = $roomVoiceParts->pluck('voice_part_id')->toArray();
            foreach ($voicePartIds as $voicePartId) {
                $voicePartDescr = VoicePart::where('id', $voicePartId)->first()->descr;
                $this->candidates[] = [
                    'roomName' => $room->room_name,
                    'candidates' => [
                        'voicePartDescr' => $voicePartDescr,
                        'candidates' => $this->getVoicePartCandidates($voicePartId, $room),
                    ]
                ];
            }
        }

        $this->studentCount = $this->setStudentCount();
    }

    private function getRooms(): Collection
    {
        return ($this->roomId)
            ? Room::where('id', $this->roomId)->get()
            : Room::where('version_id', $this->versionId)->orderBy('order_by')->get();
    }

    private function getVoicePartCandidates(int $voicePartId, Room $room): array
    {
        $candidates = Candidate::query()
            ->join('voice_parts', 'candidates.voice_part_id', '=', 'voice_parts.id')
            ->where('version_id', $this->versionId)
            ->where('status', 'registered')
            ->where('voice_part_id', $voicePartId)
            ->select('candidates.id AS candidateId', 'voice_parts.descr AS voicePartDescr')
            ->get()
            ->toArray();

        foreach ($candidates as $key => $candidate) {

            $candidateId = $candidate['candidateId'];
            $candidates[$key]['statusColors'] = $this->getStatusColors($candidateId, $room);
            $candidates[$key]['title'] = $this->getTitle(Candidate::find($candidateId), $room, $candidateId);
        }

        return $candidates;
    }

    private function getStatusColors(int $candidateId, Room $room): string
    {
        $status = CandidateAdjudicationStatusService::getRoomStatus($candidateId, $room);

        $statuses = [
            'completed' => 'bg-green-500 text-white',
            'pending' => 'bg-black text-white',
            'wip' => 'bg-yellow-400 text-black',
            'errors' => 'bg-red-600 text-yellow-400',
        ];

        return $statuses[$status];
    }

    private function getTitle(Candidate $candidate, Room $room, int $candidateId): string
    {
        $crlf = "\n";
        $str = '';
        $judges = $room->judges;
        $roomFactorCount = $this->getScoringFactorsAttribute($room)->count();

        $teacher = Teacher::find($candidate->teacher_id);
        $str .= $candidate->program_name.$crlf;
        $str .= $teacher->user->name.' @ '.$candidate->school->shortName.$crlf;

        foreach ($judges as $judge) {

            $judgeScoreCount = $judge->getCandidateScoreCount($candidateId);
            $judgeTotalScore = $judge->getCandidateTotalScore($candidateId);

            $str .= $judge->user->name
                .': '.$judgeScoreCount
                .'/'
                .$roomFactorCount
                .' ('
                .$judgeTotalScore.')'
                .$crlf;
        }

        return $str;
    }

    private function getScoringFactorsAttribute(Room $room): Collection
    {
        $roomScoreCategoriesIds = $room->roomScoreCategories->pluck('score_category_id')->toArray();

        return ScoreFactor::query()
            ->with('scoreCategory')
            ->whereIn('score_category_id', $roomScoreCategoriesIds)
            ->orderBy('score_factors.order_by')
            ->get();
    }

    private function setStudentCount(): int
    {
        $count = 0;

        foreach ($this->candidates as $room) {
            $count += (count($room['candidates']['candidates']));
        }

        return $count;
    }


}
