<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentCountsExport implements FromArray, WithHeadings
{
    private string $teacherEmail = '';
    private string $teacherName = '';
    private string $teacherPhoneMobile = '';
    private string $teacherPhoneWork = '';

    public function __construct(
        private readonly array $rows,
        private readonly Collection $voiceParts
    ) {
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        return $this->mapRows();
    }

    public function headings(): array
    {
        $headings = [
            'school',
            'teacher',
            'email',
            'phone_cell',
            'phone_mobile',
        ];

        foreach ($this->voiceParts as $voicePart) {
            $headings[] = $voicePart->abbr;
        }

        $headings[] = 'total';

        return $headings;
    }

    private function mapRows(): array
    {
        $a = [];

        foreach ($this->rows as $key => $row) {

            $this->stripHTML($row['teacherName']);

            $voicePartColumns = $this->mapVoicePartColumns($row);

            $a[$key] = [
                'school' => $row['schoolName'],
                'teacher' => $this->teacherName,
                'email' => $this->teacherEmail,
                'phoneMobile' => $this->teacherPhoneMobile,
                'phoneWork' => $this->teacherPhoneWork,
            ];

            $a[$key] = array_merge($a[$key], $voicePartColumns);

            $a[$key]['total'] = $row['total'];
        }

        return $a;
    }

    private function mapVoicePartColumns(array $row): array
    {
        $voiceParts = $row;
        //remove the first three properties from $row (counter, schoolName, teacherName)
        for ($i = 0; $i < 3; $i++) {
            array_shift($voiceParts);
        }

        array_pop($voiceParts);

        return $voiceParts;
    }

    private function stripHtml(string $teacherName): void
    {
        $parts = explode('<div', $teacherName);

        $this->teacherEmail = $this->stripHtmlComm($parts[2]);;
        $this->teacherName = $this->stripHtmlTeacherName($parts[1]);
        $this->teacherPhoneMobile = $this->stripHtmlComm($parts[3]);
        $this->teacherPhoneWork = $this->stripHtmlComm($parts[4]);
    }

    private function stripHtmlComm(string $comm): string
    {
        $leftDiv = Str::remove('class="ml-2 text-sm">', $comm);

        return Str::remove('</div>', $leftDiv);
    }

    private function stripHtmlTeacherName(string $teacherName): string
    {
        $leftDiv = Str::remove('>', $teacherName);

        return Str::remove('</div', $leftDiv);
    }
}
