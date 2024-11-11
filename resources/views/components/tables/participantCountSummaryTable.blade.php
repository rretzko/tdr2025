<style>
    #scoreSummaryTable {
        border-collapse: collapse;
        margin: auto;
    }

    #scoreSummaryTable td, th {
        border: 1px solid black;
        padding: 0 0.25rem;
    }
</style>
<table id="scoreSummaryTable">
    <thead>
    <tr>
        <th>Ensemble</th>
        @forelse($voicePartAbbrs AS $abbr)
            <th>
                {{ $abbr }}
            </th>
        @empty
            <th>No voice parts found.</th>
        @endforelse
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @forelse($ensemblesArray AS $ensemble)
        <tr>
            <td>{{ $ensemble['ensemble_name'] }}</td>
        </tr>
    @empty
        <tr>
            <td>No ensemble found</td>
        </tr>
    @endforelse
    </tbody>
</table>
