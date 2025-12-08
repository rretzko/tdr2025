<div>
    <h1>Create Scores</h1>
    <h2>
        Click the button below ONLY if you have confirmed that you are in the LOCAL environment OR
        in production AFTER you have backed up the database.
    </h2>
    @forelse($openVersionsWithoutScores AS $version)
        {{ $version->name }}
    @empty
        No versions found without current scores
    @endforelse
</div>
