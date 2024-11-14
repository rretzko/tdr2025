<div class="space-y-4 mx-4 p-2 border border-gray-s00 ">
    <div class="mx-4 flex flex-col md:flex-row md:space-x-4">
        @foreach($voiceParts AS $voicePart)
            <div class="items-center w-fit md:w-full">
                <input type="radio" wire:model.live="voicePartId" value="{{ $voicePart->id }}">
                <label>{{ $voicePart->descr }}</label>
            </div>
        @endforeach
    </div>

    <div class="flex justify-end mr-8 border border-white border-t-gray-200 p-2 text-blue-500">
        <button type="button" wire.click="clickPrinter">
            @include('components.heroicons.printer')
        </button>
    </div>

    <div class=" w-full">
        <style>
            #scores {
                border-collapse: collapse;
                margin: auto;
                margin-top: 0.5rem;
                width: fit-content;
            }

            #scores td, th {
                padding: 0 0.25rem;
                border: 1px solid black;
                text-align: center;
            }
        </style>
        <table id="scores">
            <thead>
            <tr>
                <th>Id</th>
                <th>VP</th>
                @for($i=0; $i<$judgeCount; $i++)
                    @forelse($factors AS $factor)
                        <th>{{ $factor->abbr }}</th>
                    @empty
                        <th>No score factors found.</th>
                    @endforelse
                @endfor
                <th>Total</th>
                <th>Result</th>
            </tr>
            </thead>
            <tbody>
            @forelse($rows AS $row)
                <tr>
                    <td style="text-align: left;">{{ $row->id }}</td>
                    <td>{{ $row->voicePartAbbr }}</td>
                    @forelse($row->scores AS $score)
                        <td class=" @if($score === 0) text-gray-300 @endif ">
                            {{ $score }}
                        </td>
                    @empty
                        <td>No scores found</td>
                    @endforelse
                    <td class=" @if(! $row->total) text-gray-300 @endif">
                        {{ $row->total ?: 0 }}
                    </td>
                    <td>
                        {{ $row->acceptance_abbr ?: 'ns' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td>No candidates found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>


</div>
