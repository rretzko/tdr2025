<div class=" w-full p-2 bg-orange-100 rounded-lg">
    <div class="flex flex-row justify-between mr-4 mb-2">
        <header class="">
            Upload Library Items Form
        </header>
    </div>

    {{-- INSTRUCTIONS --}}
    <div class="instructions border border-gray-300 text-sm m-2 p-2 rounded-lg shadow-lg">
        Use this form to:
        <ul>
            <li>Download a .csv file template, and</li>
            <li>Upload your list of items to be included in <b>{{ $libraryName }}</b>.</li>
        </ul>
        Note the following:
        <ul>
            <li>
                The system uses the title, type, voicing, and artist columns (composer, arranger, etc.)
                to match against any existing library items stored in TheDirectorsRoom.com database.
                In order to avoid creating duplicate records, please review the your file for correct
                spelling.
            </li>
            <li>The system recognizes the following types of library items:
                <ul>
                    <li>Octavo (this is the default if no type is found)</li>
                    <li>Medley</li>
                    <li>Book</li>
                    <li>Digital</li>
                    <li>CD</li>
                    <li>DVD</li>
                    <li>Cassette</li>
                    <li>Vinyl</li>
                </ul>
                If the "type" column is left blank, the system will default to "octavo".
            </li>
            <li>
                Voicings should match the published page and DO NOT include accompaniments or any other comments.
                If the "voicing" column is left blank, the system will default to "satb".
            </li>
            <li>Artist names (composer, arranger, etc.) should use proper capitalization.</li>
            </li>
            <li>Artist columns should include the full name as used on the published copy.</li>
            <li>Artist types with two names (ex. Carole Bayer Sager and David Foster) should be separated
                with either "and" or an ampersand (&).
            </li>
            <li>Artist types with more that two names (ex. Joe Beal, Jim Boothe, and Johnny Marks) should be
                separated with commas and then use either "and" or an ampersand (&) for the final name.
            </li>
            <li>
                Tags are any key words that you might use to search for this kind of song.
                For standardization, these are all lower case and separated by a comma.
                Examples are: pop, holiday, broadway, french, doo-wop, a cappella, rock, etc.
            </li>
            <li>
                Use the "copies" column to record the number of copies on-hand. If the copes column is
                left blank, the system will default to "1".
            </li>
            <li>
                Use the "price" column to record the last price paid. DO NOT include the dollar sign.
                If the price column is left blank, the system will default to "0".
            </li>
            <li>
                There are three "location" columns to provide space for your personal filing system.
                You may use these columns to record any method you may currently use.
                For example, if you currently store your music in file cabinets, you might use the
                three columns to indicate File Cabinet 1, Drawer 3, file tab G.
            </li>
            <li>
                <b><u>Review your file and remove all duplicate rows!</u></b> Duplicate rows will
                <u>overwrite</u> any preceding row information.
            </li>
        </ul>
    </div>

    {{-- TEMPLATE DOWNLOAD BUTTON --}}
    <div class="flex w-full justify-center">
        <a href="{{ $uploadTemplateUrl }}" target="_blank">
            <button class="bg-indigo-800 text-yellow-400 text-sm rounded-lg px-2 shadow-lg">
                Click here to download a .csv file template.
            </button>
        </a>
    </div>

    <div class="flex flex-col m-4">
        <form wire:submit="clickUploadCsv" class="flex flex-col border border-gray-300 p-2 rounded-lg">
            <input type="file" id="myFile" wire:model="uploadedFileContainer" accept=".csv" class="">
            <hint class="text-sm italic mb-2">Max file size: 4MB.</hint>
            <div class="text-sm text-red-500 mb-2">
                @if($uploadedMaxFileSizeExceeded)
                    {{ $uploadedMaxFileSizeExceededMessage }}
                @endif
            </div>
            @error('uploadedFileContainer') <span class="error">{{ $message }}</span> @enderror
            <button type="submit" wire:loading.attr="disabled"
                    class="bg-black text-white rounded-lg w-fit px-2 disabled:bg-gray-500"
            >
                Upload
            </button>
            <div wire:loading wire:target="uploadedFileContainer" class="text-sm italic">
                Please wait while we prepare the file...
            </div>
            <div class="advisory bg-red-100 p-2 w-1/2">
                To reduce wait time, you will be returned to your Items page while your file is processed
                in the background. Please wait one minute and then refresh the Items page to review the
                results of the upload.
            </div>
        </form>
    </div>

</div>
