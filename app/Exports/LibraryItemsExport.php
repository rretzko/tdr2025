<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LibraryItemsExport implements FromArray, WithHeadings
{
    private array $rows = [];

    /**
     * 0 => array:15 [▼
     * "id" => 99
     * "count" => 1
     * "libItemId" => 138
     * "title" => "1000 Beautiful Things"
     * "alpha" => "1000 Beautiful Things"
     * "item_type" => "octavo"
     * "composerName" => "Lennox, Annie"
     * "arrangerName" => "Johnson, Lane"
     * "wamName" => null
     * "wordsName" => null
     * "musicName" => null
     * "choreographerName" => null
     * "authorName" => null
     * "voicingDescr" => "satb"
     * "medleyTitles" => null
     * ]
     * @param  array  $items
     */
    public function __construct(
        private readonly array $items,
        private readonly array $tags,
        private readonly array $urls,
        private readonly array $perfs, //performances
        private readonly array $docs, //collateral docs
    )
    {
        $this->buildRows();
    }

    private function buildRows(): void
    {
        foreach ($this->items as $item) {

            $libItemId = $item['libItemId'];
            $docs = $this->parseDocs($this->docs[$libItemId]);
            $urls = $this->parseUrls($this->urls[$libItemId]);

            $this->rows[] = [
                $libItemId,
                $item['title'],
                $item['voicingDescr'],
                $item['count'],
                $item['composerName'],
                $item['arrangerName'],
                $item['wamName'],
                $item['wordsName'],
                $item['musicName'],
                $item['choreographerName'],
                implode(', ', $this->tags[$libItemId]),
                $docs,
                $urls,
                implode(', ', $this->perfs[$libItemId]),
            ];
        }
    }

    private function parseDocs(array $docs): string
    {
        //early exit
        if (empty($docs)) {
            return '';
        }

        $strs = [];
        $aws = 'https://auditionsuite-production.s3.amazonaws.com/';

        foreach ($docs as $doc) {

            $strs[] = $aws.$doc['url'].' ('.$doc['label'].')';
        }

        return implode(', ', $strs);
    }

    private function parseUrls(array $urls): string
    {
        //early exit
        if (empty($urls)) {
            return '';
        }

        $strs = [];

        foreach ($urls as $url) {

            $strs[] = $url['url'].' ('.$url['label'].')';
        }

        return implode(', ', $strs);
    }

    /**
     * @return array
     */
    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'item-id',
            'title',
            'voicing',
            'count',
            'composer',
            'arranger',
            'words+music',
            'words',
            'music',
            'choreo',
            'tags',
            'docs',
            'web',
            'perf',
        ];
    }
}
