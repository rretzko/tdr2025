<?php

namespace App\Exports;

use App\Models\Schools\Teacher;
use App\Models\Students\Student;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromQuery, WithHeadings
{
    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return DB::table('students')
            ->join('student_teacher', 'students.id', '=', 'student_teacher.student_id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('voice_parts', 'students.voice_part_id', '=', 'voice_parts.id')
            ->join('pronouns', 'users.pronoun_id', '=', 'pronouns.id')
            ->leftJoin('phone_numbers AS mobile', function ($join) {
                $join->on('users.id', '=', 'mobile.user_id')
                    ->where('mobile.phone_type', '=', 'mobile');
            })
            ->leftJoin('phone_numbers AS home', function ($join) {
                $join->on('users.id', '=', 'home.user_id')
                    ->where('home.phone_type', '=', 'home');
            })
            ->leftJoin('addresses', function ($join) {
                $join->on('users.id', '=', 'addresses.user_id');
            })
            ->leftJoin('geostates', function ($join) {
                $join->on('addresses.geostate_id', '=', 'geostates.id');
            })
            ->leftJoin('emergency_contacts', function ($join) {
                $join->on('students.id', '=', 'emergency_contacts.student_id');
            })
            ->leftJoin('emergency_contact_types', function ($join) {
                $join->on('emergency_contacts.emergency_contact_type_id', '=', 'emergency_contact_types.id');
            })
            ->where('student_teacher.teacher_id', auth()->id())
            ->select('users.last_name', 'users.first_name', 'users.middle_name', 'users.suffix_name',
                'users.email', 'pronouns.descr AS pronounDescr',
                'students.class_of', 'voice_parts.descr', 'students.birthday', 'students.height', 'students.shirt_size',
                'mobile.phone_number AS phoneMobile', 'home.phone_number AS phoneHome',
                'addresses.address1', 'addresses.address2', 'addresses.city', 'geostates.abbr', 'addresses.postal_code',
                'emergency_contact_types.relationship', 'emergency_contacts.name',
                'emergency_contacts.email AS ecEmail',
                'emergency_contacts.best_phone', 'emergency_contacts.phone_mobile', 'emergency_contacts.phone_home',
                'emergency_contacts.phone_work')
            ->orderBy('users.last_name');

    }

    public function headings(): array
    {
        return [
            'last', 'first', 'middle', 'suffix', 'email', 'pronoun',
            'classOf', 'voice part', 'birthday', 'height', 'shirt size',
            'cellphone', 'homephone', 'address1', 'address2', 'city', 'state', 'zip',
            'ec_type', 'ec_name', 'ec_email', 'ec_best_phone', 'ec_cell', 'ec_home', 'ec_work'
        ];
    }
}
