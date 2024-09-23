<div>

    <style>
        .pageBreak {
            page-break-after: always;
        }
    </style>

    <h2 style="text-align: center;">
        {{ $dto['versionName'] }}
    </h2>
    <h1 class="text-lg font-semibold" style="text-align: center; ">
        Adjudication backup for: {{ $room['roomName'] }}
    </h1>

    <h2 style="text-align: center;">
        {{ $judge->name }}: {{ ucwords($judge->judge_type) }}
    </h2>

    <div class="pageBreak"></div>

</div>
