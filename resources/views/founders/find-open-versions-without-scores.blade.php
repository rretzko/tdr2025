<div class="flex flex-col">
    <h1>Create Scores</h1>
    <h2>
        Click the button below ONLY if:
        <ul>
            <li>you have confirmed that you are in the <span style="color: red;">LOCAL</span> environment, OR</li>
            <li>in production <span style="color: red;">AFTER you have backed up</span> the database.</li>
        </ul>
    </h2>
    @forelse($openVersionsWithoutScores AS $version)
        <div>
            <a href="{{ route('founder.create-scores', ['version' => $version]) }}" class="text-blue-700">
                {{ $version->name }}
            </a>
        </div>
    @empty
        No versions found without current scores
    @endforelse
</div>
