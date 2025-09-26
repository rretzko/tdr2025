<?php

namespace App\Services\Libraries;

use Aws\Textract\TextractClient;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser as PdfParser;
use Storage;
use Str;

class SheetMusicParser
{
    private static array $bys = [
      'arranged by' => 0,
    ];

    /**
     * @param string $text
     * @return array return $text as an array
     */
    protected static function createSearchArray(string $text): array
    {
        $slashNRemoved = str_replace('\n', "\n", $text);

        return collect(preg_split('/\R+/', $slashNRemoved)) // split on any newline sequence (CR, LF, CRLF, unicode newlines)
        ->map(fn($l) => trim($l)) // trim whitespace
        ->filter(fn($l) => $l !== '') // remove empty lines (omit this if you want to keep empties)
        ->values() // reindex 0..n-1
        ->all(); // get plain array
    }

    /**
     * Extract metadata from either PDF (text) or image (OCR via Textract).
     *
     * @param string $filePath Local path (for PDFs) or S3 key (for images).
     * @param bool   $fromS3   If true, assumes file is in S3 and uses Textract.
     *
     * $text example:
     * """
     * Elijah Rock
     * For SATB (divisi) a cappella
     * Performance Time: Approx. 3:05
     * Traditional Spiritual
     * Arranged by
     * MOSES G. HOGAN
     * Moderato (J = 100)
     * Soprano
     * C
     * Alto
     * marcato
     * div.
     * V
     * Tenor
     * ...
     * """
     */
    public static function fromFile(string $filePath, bool $fromS3 = false): array
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if ($extension === 'pdf' && !$fromS3) {
            // Handle text-based PDFs locally
            $text = self::extractFromPdf($filePath);
        } else {
            // Handle images (or PDFs treated as images) via Textract
            $text = self::extractFromS3WithTextract($filePath);
        }

        $searchArray = self::createSearchArray($text);

        return self::parseTextFromArray($searchArray);
    }

    /**
     * Extract text from a PDF file (digital PDFs only).
     */
    protected static function extractFromPdf(string $filePath): string
    {
        $parser = new PdfParser();
        $pdf = $parser->parseFile(Storage::disk('s3')->url($filePath));

        if(! $pdf->getText()){
            return self::extractFromS3WithTextract($filePath);
        }

        return $pdf->getText();
    }

    /**
     * Extract text from S3-stored file using AWS Textract.
     */
    protected static function extractFromS3WithTextract(string $s3Key): string
    {
        $client = new TextractClient([
            'version' => 'latest',
            'region'  => config('filesystems.disks.s3.region'),
            'credentials' => [
                'key'    => config('filesystems.disks.s3.key'), // env('AWS_ACCESS_KEY_ID'),
                'secret' => config('filesystems.disks.s3.secret') //env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $result = $client->analyzeDocument([
            'Document' => [
                'S3Object' => [
                    'Bucket' => config('filesystems.disks.s3.bucket'), //env('AWS_BUCKET'),
                    'Name'   => $s3Key,
                ],
            ],
            'FeatureTypes' => ['FORMS', 'TABLES'],
        ]);

        $text = '';
        foreach ($result['Blocks'] as $block) {
            if ($block['BlockType'] === 'LINE' && isset($block['Text'])) {
                $text .= $block['Text'] . "\n";
            }
        }

        return $text;
    }

    /**
     * Parse raw text into structured metadata.
     */
    protected static function parseTextFromArray(array $searchArray): array
    {
        $trimmed = array_slice($searchArray, 0, 25);

        $data = [
            'title'         => null,
            'composer'      => [],
            'lyricist'      => [],
            'arranger'      => [],
            'choreographer' => [],
            'words and music' => [],
            'words' =>  [],
            'music' =>  [],
            'lyrics' => [],
            'mm' => [], //metronome marking
            'tempo' => [],
            'subtitle' => [],
        ];

        //title
        $data['title'] = self::findTitle($trimmed);

        //arranger
        $data['arranger'] = self::findArranger($trimmed, $data);
dd($data);
dd($trimmed);
        //composer
        $data['composer'] = self::findComposer($trimmed, $data);

        return $data;
    }

    /**
     * Return the person name suffixed to 'arranged by' or immediately following 'arranged by'
     * @param array $trimmed
     * @param array $data
     * @return string
     */
    protected static function findArranger(array &$trimmed, array $data): string
    {
        foreach($trimmed as $artist){

            $lowerArtist = strtolower($artist);

            if(Str::startsWith($lowerArtist, 'arrang')){

                $artistName = self::parseArtistName('arranged by', $artist, $trimmed);

                self::removeTrimmedRow($trimmed, $artist);

                return $artistName;
            }
        }

        return ''; //no arranger found
    }

    /**
     * Return the first name NOT prefixed with an artist modifier
     *  e.g. 'arranged by', 'words by', etc.
     * @param array $trimmed
     * @param array $data
     * @return string
     */
    protected static function findComposer(array &$trimmed, array $data): string
    {
        foreach($trimmed as $artist){
          if((! array_sum(self::$bys)) && self::isPerson($artist)){

              self::removeTrimmedRow($trimmed, $artist);
              return $artist;
            }
        }

        return ''; //no composer found
    }

    protected static function isPerson(string $str): bool
    {
        $classifier = new EntityClassifierService();

        return $classifier->isPerson($str);
    }

    /**
     * Return the first lined that DOES NOT qualify as an exclusion (break;)
     * @param array $trimmed
     * @return string
     */
    protected static function findTitle(array &$trimmed): string
    {
        foreach($trimmed as $title){

            if(! static::isSkippableTitle($title)){

                self::removeTrimmedRow($trimmed, $title);
                return $title;
            }
        }

        return 'no title found';
    }

    protected static function isSkippableTitle(string $title): bool
    {
        $lowerTitle = Str::lower($title);

        if(Str::startsWith($lowerTitle, ['commissioned by', 'dedicated to'])){
            return true;
        }

        if(Str::startsWith($lowerTitle, '2') && (Str::length($lowerTitle) === 1)){
            return true;
        }

        return false;
    }

    /**
     * artistName is either a suffix to a description (ex. "arranged by") or
     * follows the description on the next row of $trimmed
     * @param string $artist
     * @param array $trimmed
     * @return string
     */
    protected static function parseArtistName(string $artistType, string $artist, array &$trimmed): string
    {
        $types = [
            'arranged by' => [
                'arranged by:',
                'arranged by',
                'arr.',
                'arr',
            ],
        ];

        $testTypes = $types[$artistType];

        //remove prefix from $artist
        foreach($testTypes as $testType) {
            $lowerArtist = strtolower($artist);
            if (Str::startsWith($testType, $lowerArtist)) {

                $removeType = str_ireplace($artistType, "", $artist);
                $trimmedArtist = trim($removeType);

                //test if artist is on the same line or on the following line
                $key = 0;
                if (!Str::length($trimmedArtist)) {
                    $key = array_search($artist, $trimmed, true);
                    $trimmedArtist = trim($trimmed[$key + 1]);
                }

                if (self::isPerson($trimmedArtist)) {
                    if ($key) {
                        self::removeTrimmedRow($trimmed, $trimmed[$key + 1]);
                        return self::normalizeName($trimmedArtist);
                    }
                }
            }
        }

        return '';
    }
    protected static function removeTrimmedRow(array &$trimmed, string $value): void
    {
        if (($key = array_search($value, $trimmed, true)) !== false) {
            unset($trimmed[$key]);
            $trimmed = array_values($trimmed); // reindex if you want 0..n-1 keys
        }
    }

    /**
     * Parse raw text into structured metadata.
     */
    protected static function parseText(string $text): array
    {
        $data = [
            'title'         => null,
            'composer'      => [],
            'lyricist'      => [],
            'arranger'      => [],
            'choreographer' => [],
            'words and music' => [],
            'words' =>  [],
            'music' =>  [],
            'lyrics' => [],
            'mm' => [], //metronome marking
            'tempo' => [],
            'subtitle' => [],
        ];

        // Extract title (first non-empty line)
        //text = \n separated list of text values
        //matches = two element array with
        // [0] = matching non-empty line including any trailing \n suffix, and (ex. Elijah Rock\n)
        // [1] = matching non-empty line exclusive of any trailing \n suffix (ex. Elijah Rock)

        if (preg_match('/^(.*?)\n/', $text, $matches)) {
            $data['title'] = self::normalizeTitle(trim($matches[1]));
        }

        // Composer(s)
        if (preg_match('/Music by (.+)/i', $text, $matches)) {
            $data['composer'] = self::splitAndNormalizeNames($matches[1]);
        }

        // Lyricist(s)
        if (preg_match('/Words by (.+)/i', $text, $matches)) {
            $data['lyricist'] = self::splitAndNormalizeNames($matches[1]);
        }

        // Arranger(s)
        if (preg_match('/Arranged by (.+)/i', $text, $matches)) {
            dd($text);
            $data['arranger'] = self::splitAndNormalizeNames($matches[1]);
        }

        // Choreographer(s)
        if (preg_match('/Choreographed by (.+)/i', $text, $matches)) {
            $data['choreographer'] = self::splitAndNormalizeNames($matches[1]);
        }

        // Words and Music(s)
        if (preg_match('/Words and Music by (.+)/i', $text, $matches)) {
            $data['words and music'] = self::splitAndNormalizeNames($matches[1]);
        }

        // Words(s)
        if (preg_match('/Words by (.+)/i', $text, $matches)) {
            $data['words'] = self::splitAndNormalizeNames($matches[1]);
        }

        // Lyric(s)
        if (preg_match('/Lyrics by (.+)/i', $text, $matches)) {
            $data['lyrics'] = self::splitAndNormalizeNames($matches[1]);
        }

        // Music(s)
        if (preg_match('/Music by (.+)/i', $text, $matches)) {
            $data['music'] = self::splitAndNormalizeNames($matches[1]);
        }

        return $data;
    }

    /**
     * Normalize a string containing multiple names.
     */
    protected static function splitAndNormalizeNames(string $raw): array
    {
        $parts = preg_split('/\s*(?:,|&|and)\s*/i', $raw);

        $names = [];
        foreach ($parts as $part) {
            $clean = trim($part);
            if ($clean) {
                $names[] = self::normalizeName($clean);
            }
        }

        return $names;
    }

    /**
     * Normalize a single name string.
     */
    protected static function normalizeName(string $name): string
    {
        $name = strtolower(trim($name));
        $name = ucwords($name, " -'");

        // Fix "Mc"/"Mac"
        $name = preg_replace_callback(
            '/\b(Mc|Mac)([a-z])/',
            fn($m) => $m[1] . strtoupper($m[2]),
            $name
        );

        return $name;
    }

    /**
     * Normalize a title string.
     */
    protected static function normalizeTitle(string $title): string
    {
        $title = strtolower($title);
        return ucwords($title);
    }
}
