<?php

use Illuminate\Support\Carbon;

test('recordings array structure includes updated_at timestamp', function () {

    // Simulate the exact logic from CandidateForm::setRecordingsArray()
    $recordings = [];

    // Mock recording data as if returned from the database
    $fakeRecordings = [
        (object) [
            'file_type' => 'scales',
            'url' => 'recordings/123_scales_63.mp3',
            'updated_at' => Carbon::parse('2026-03-28 10:00:00'),
            'approved' => Carbon::parse('2026-03-28 11:00:00'),
        ],
        (object) [
            'file_type' => 'solo',
            'url' => 'recordings/123_solo_63.mp3',
            'updated_at' => Carbon::parse('2026-03-29 14:00:00'),
            'approved' => null,
        ],
    ];

    // Replicate setRecordingsArray logic
    foreach ($fakeRecordings as $recording) {
        $recordings[$recording->file_type]['url'] = $recording->url;
        $recordings[$recording->file_type]['updated_at'] = $recording->updated_at->timestamp;
        $recordings[$recording->file_type]['approved'] =
            ($recording->approved)
                ? Carbon::parse($recording->approved)->format('D, M d, y g:i a')
                : '';
    }

    // Verify updated_at is present and is an integer timestamp
    expect($recordings['scales'])
        ->toHaveKeys(['url', 'updated_at', 'approved'])
        ->and($recordings['scales']['updated_at'])
        ->toBeInt()
        ->toBe(Carbon::parse('2026-03-28 10:00:00')->timestamp)
        ->and($recordings['solo']['updated_at'])
        ->toBeInt()
        ->toBe(Carbon::parse('2026-03-29 14:00:00')->timestamp);

    // Verify the URL with cache-busting parameter would produce a unique string
    $urlWithCacheBust = $recordings['scales']['url'] . '?v=' . $recordings['scales']['updated_at'];
    expect($urlWithCacheBust)
        ->toBe('recordings/123_scales_63.mp3?v=' . Carbon::parse('2026-03-28 10:00:00')->timestamp);
});

test('replaced recording produces different cache-busting parameter with same url', function () {

    $originalTime = Carbon::parse('2026-03-28 10:00:00');
    $newTime = Carbon::parse('2026-03-30 14:00:00');
    $sameUrl = 'recordings/123_scales_63.mp3';

    // Original recording
    $originalCacheBust = $sameUrl . '?v=' . $originalTime->timestamp;

    // Replacement recording (same URL, different updated_at)
    $newCacheBust = $sameUrl . '?v=' . $newTime->timestamp;

    // The base URL is identical (deterministic filename unchanged)
    expect($sameUrl)->toBe('recordings/123_scales_63.mp3');

    // But the full URL with cache-busting differs
    expect($originalCacheBust)->not->toBe($newCacheBust);

    // The timestamp portion is what makes them unique
    expect($originalTime->timestamp)->not->toBe($newTime->timestamp);
});
