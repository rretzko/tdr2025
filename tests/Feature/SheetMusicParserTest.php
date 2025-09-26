<?php

use App\Services\SheetMusicParser;

it('parses single credits correctly', function () {
    $sampleText = <<<EOT
A Lovely Way To Spend An Evening
Words by HAROLD ADAMSON
Music by JIMMY McHUGH
Arranged by MAC HUFF
Choreographed by JOHN JACOBSON
EOT;

    $result = (new ReflectionClass(SheetMusicParser::class))
        ->getMethod('parseText')
        ->invoke(null, $sampleText);

    expect($result)->toMatchArray([
        'title'        => 'A Lovely Way To Spend An Evening',
        'composer'     => ['Jimmy McHugh'],
        'lyricist'     => ['Harold Adamson'],
        'arranger'     => ['Mac Huff'],
        'choreographer'=> ['John Jacobson'],
    ]);
});

it('parses multiple credits correctly', function () {
    $sampleText = <<<EOT
Some Song
Words by HAROLD ADAMSON and DOROTHY FIELDS
Music by JIMMY McHUGH & JEROME KERN
Arranged by MAC HUFF, ROGER EMERSON
EOT;

    $result = (new ReflectionClass(SheetMusicParser::class))
        ->getMethod('parseText')
        ->invoke(null, $sampleText);

    expect($result)->toMatchArray([
        'title'        => 'Some Song',
        'composer'     => ['Jimmy McHugh', 'Jerome Kern'],
        'lyricist'     => ['Harold Adamson', 'Dorothy Fields'],
        'arranger'     => ['Mac Huff', 'Roger Emerson'],
        'choreographer'=> [],
    ]);
});

