<div class="space-y-4 mx-4 p-2 border border-gray-s00 ">
    <div class="mx-4 flex flex-col md:flex-row md:space-x-4">
        @foreach($voiceParts AS $voicePart)
            <div class="items-center w-fit md:w-full">
                <input type="radio" wire:model="voicePartId" value="{{ $voicePart->id }}">
                <label>{{ $voicePart->descr }}</label>
            </div>
        @endforeach
    </div>

    <div class="border border-white border-t-gray-200 p-2 w-full">
        <style>
            #scores {
                border-collapse: collapse;
                width: fit-content;
            }

            #scores td, th {
                padding: 0 0.25rem;
                border: 1px solid black;
                text-align: left;
            }
        </style>
        <table id="scores">
            <thead>
            <tr>
                <th>Headers</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Body</td>
            </tr>
            </tbody>
        </table>
    </div>


</div>
