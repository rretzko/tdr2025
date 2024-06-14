<?php

namespace App\Data;

use App\Models\PageInstruction;
use App\Models\ViewCard;

class ViewDataFactory extends aViewData
{
    public function __construct(public readonly string $__method, public readonly mixed $id = 0)
    {
        parent::__construct($this->__method);

        $this->init();
    }

    private function init(): void
    {
        //if $id, ensure that $this->dto['id'] is an integer value
        $this->dto['id'] = (($this->id) && (is_object($this->id)))
            ? (int) $this->id->id
            : (int) $this->id;

        //include the count of schools to determine if the breadcrumbs should be included
        //a newly registered user will have NO schools and should not be allowed to do
        //anything else until a school is added
        $this->dto['schoolCount'] = auth()->user()->teacher->schools->count();

        if ($this->viewPage->id) {
            $this->dto['pageName'] = 'pages.'.$this->viewPage->page_name.'Page';
            $this->dto['header'] = $this->viewPage->header;
//            Log::info('info header: '.$this->dto['header']);
//            Log::error('error header: '.$this->dto['header']);
//            if (is_null($this->dto['header'])) {
//                Log::info('info: is_null('.$this->dto['header'].')');
//                Log::error('error: is_null('.$this->dto['header'].')');
//            }

            $this->dto['pageInstructions'] = $this->decodeInstructions(PageInstruction::where('header',
                $this->dto['header'])->first()->instructions);

            //retrieve page components ex. cards
            foreach ($this->getComponents() as $component) {

                $method = 'get'.ucfirst($component);

                $this->dto[$component] = $this->$method();
            }

        } else { //user default values

            $this->dto['pageInstructions'] = '';
            $this->dto['pageName'] = 'pages.dashboardPage';
            $this->dto['header'] = 'unknown';
            $this->dto['cards'] = [];
        }
    }

    /**
     * Decode instructions that were created through the FilamentPHP Rich Text object
     * @param $encoded
     * @return string
     */
    private function decodeInstructions($encoded): string
    {
        return html_entity_decode($encoded);
    }

    private function getColumnHeaders(): array
    {
        $headers = [
            //'schools' => ['name', 'address', 'grades', 'active?', 'email', 'verified', 'i teach',],
        ];

        return array_key_exists($this->viewPage->header, $headers) ? $headers[$this->viewPage->header] : [];
    }

    private function getComponents(): array
    {
        $components = [
            'dashboard' => ['cards'],
            'livewire' => ['livewireComponent'],
            'table' => ['columnHeaders', 'rows'],
        ];

        return $components[$this->viewPage->page_name];
    }

    public function getDto(): array
    {
        return $this->dto;
    }

    /**
     * Static cards are stored in the database and accessed by query, otherwise
     * Cards are created dynamically via Data/Cards/<$this->dto['header'].Card file ex: Data/Cards/SchoolsCard
     *
     * @return array
     */
    private function getCards(): array
    {
        $default = []; //no cards

        if (ViewCard::query()
            ->where('header', $this->dto['header'])
            ->exists()) {

            return ViewCard::query()
                ->where('header', $this->dto['header'])
                ->orderBy('order_by')
                ->get()
                ->toArray();
        }
//        else {

        //ex: \App\Data\Cards\SchoolsCard
//            $model = '\App\Data\Cards\\'.$this->dto['header'].'Card';
//
//            $src = new $model();
//
//            return $src->getCards();
//        }
        return $default;
    }

    private function getLivewireComponent(): string
    {
        $components = [
            'new school' => 'schools.school-create-component',
            'schools' => 'schools.schools-table-component',
            'school edit' => 'schools.school-edit-component',

            'new student' => 'students.student-create-component',
            'students' => 'students.students-table-component',
            'student edit' => 'students.student-edit-component',
            'student comms edit' => 'students.student-comms-edit-component',
            'student ec edit' => 'students.student-e-c-edit-component',
            'student reset password' => 'students.student-reset-password-component',

            'ensembles' => 'ensembles.ensembles-table-component',
            'ensemble create' => 'ensembles.ensemble-create-component',
            'ensemble edit' => 'ensembles.ensemble-edit-component',

            'assets' => 'ensembles.assets.assets-table-component',
            'asset create' => 'ensembles.assets.asset-create-component',
            'asset edit' => 'ensembles.assets.asset-edit-component',

            'members' => 'ensembles.members.members-table-component',
            'member create' => 'ensembles.members.member-create-component',
            'member edit' => 'ensembles.members.member-edit-component',
        ];

        return $components[$this->viewPage->header];
    }

    private function getRows(): array
    {
        $rows = [];

//        $schools = Teacher::find(auth()->id())->schools->sortBy('name');
//
//        foreach ($schools as $school) {
//
//            $rows = [
//                'schools' => $this->rowsSchools(),
//            ];
//        }

        return array_key_exists($this->dto['header'], $rows) ? $rows[$this->dto['header']] : [];
    }

//    private function rowsSchools(): array
//    {
//        $a = [];
//        $schools = Teacher::find(auth()->id())->schools;
//
//        foreach($schools AS $key => $school){
//
//            $schoolTeacher = SchoolTeacher::query()
//                ->where('school_id', $school->id)
//                ->where('teacher_id', auth()->id())
//                ->first();
//
//            $gradesITeach = GradesITeach::query()
//                ->where('school_id', $school->id)
//                ->where('teacher_id', auth()->id())
//                ->pluck('grade')
//                ->toArray();
//
//            $a[$key] = [
//                [$school->name, ''],
//                [$school->address, ''],
//                [(! is_null($school->grades)) ? implode(', ', $school->grades) : 'none', ''],
//                ($schoolTeacher->active
//                    ? [$this->checkBadge(), 'flex justify-center items-center']
//                    : [$this->thumbsDown(), 'flex justify-center items-center']),
//                [$schoolTeacher->email, ''],
//                (is_null($schoolTeacher->email_verified_at)
//                    ? [$this->thumbsDown(), 'flex justify-center items-center']
//                    : [$this->checkBadge(), 'flex justify-center items-center']),
//                [(! empty($gradesITeach) ? implode(', ', $gradesITeach) : 'none'), ''],
//            ];
//        }
//
//        return $a;
//    }

///** HEROICONS ****************************************************************/
//
//    private function checkBadge(): string
//    {
//        return '<span class="text-green-600">
//<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
//     class="w-6 h-6">
//    <path stroke-linecap="round" stroke-linejoin="round"
//          d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/>
//</svg>
//</span>';
//
//    }
//    private function thumbsDown(): string
//    {
//        return '<span class="text-red-500">
//<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
//    <path stroke-linecap="round" stroke-linejoin="round" d="M7.498 15.25H4.372c-1.026 0-1.945-.694-2.054-1.715a12.137 12.137 0 0 1-.068-1.285c0-2.848.992-5.464 2.649-7.521C5.287 4.247 5.886 4 6.504 4h4.016a4.5 4.5 0 0 1 1.423.23l3.114 1.04a4.5 4.5 0 0 0 1.423.23h1.294M7.498 15.25c.618 0 .991.724.725 1.282A7.471 7.471 0 0 0 7.5 19.75 2.25 2.25 0 0 0 9.75 22a.75.75 0 0 0 .75-.75v-.633c0-.573.11-1.14.322-1.672.304-.76.93-1.33 1.653-1.715a9.04 9.04 0 0 0 2.86-2.4c.498-.634 1.226-1.08 2.032-1.08h.384m-10.253 1.5H9.7m8.075-9.75c.01.05.027.1.05.148.593 1.2.925 2.55.925 3.977 0 1.487-.36 2.89-.999 4.125m.023-8.25c-.076-.365.183-.75.575-.75h.908c.889 0 1.713.518 1.972 1.368.339 1.11.521 2.287.521 3.507 0 1.553-.295 3.036-.831 4.398-.306.774-1.086 1.227-1.918 1.227h-1.053c-.472 0-.745-.556-.5-.96a8.95 8.95 0 0 0 .303-.54" />
//</svg></span>';
//    }
//
//    private function thumbsUp(): string
//    {
//        return '<span class="text-green-600">
//<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
//    <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
//</svg></span>';
//    }
//
}
