<div class=" w-1/2 p-2 bg-orange-100 rounded-lg">
    <div class="flex flex-row justify-between mr-4 mb-2">
        <header class="">
            Upload Student Members Form
        </header>
        <button wire:click="hideEnsembleStudentRoster(true)" class="text-red-500">
            Hide...
        </button>
    </div>

    {{-- INSTRUCTIONS --}}
    <div class="instructions border border-gray-300 text-sm m-2 p-2 rounded-lg shadow-lg">
        Use this page to:
        <ul>
            <li>Download a .csv file template, and</li>
            <li>Upload your roster of students for the selected ensemble.</li>
        </ul>
        Note the following:
        <ul>
            <li>
                The system uses the first name, last name, and email fields to match against any student records
                stored in TheDirectorsRoom.com database. In order to avoid creating duplicate student records,
                please ensure that these values in the .csv file match <b><u>exactly</u></b> to the information from
                the <a href="{{ route('students') }}" class="text-blue-500">Students</a> module.
            </li>
            <li>The system uses the ensemble name and school year to attach the student to the correct ensemble and
                in the correct year. Please ensure that the ensemble name is <b>{{ $ensembleName }}</b> and that
                the school year is <b>{{ $program->school_year }}</b>.
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
        <form wire:submit="clickImportNewMembers" class="flex flex-col border border-gray-300 p-2 rounded-lg">
            <input type="file" id="myFile" wire:model="uploadedFileContainer" accept=".csv" class="">
            <hint class="text-sm italic mb-2">Max file size: 4MB.</hint>
            <div class="text-sm text-red-500 mb-2">
                @if($uploadedMaxFileSizeExceeded)
                    {{ $uploadedMaxFileSizeExceededMessage }}
                @endif
            </div>
            @error('uploadedFileContainer') <span class="error">{{ $message }}</span> @enderror
            <button type="submit" wire:loading.attr="disabled"
                    class="bg-black text-white rounded-lg px-2 disabled:bg-gray-500"
            >
                Upload
            </button>
            <div wire:loading wire:target="uploadedFileContainer" class="text-sm italic">
                Please wait while we prepare the file...
            </div>
        </form>
    </div>

</div>
