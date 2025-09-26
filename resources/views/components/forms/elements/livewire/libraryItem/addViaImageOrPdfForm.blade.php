<div class=" w-full p-2 bg-orange-100 rounded-lg">
    <div class="flex flex-row justify-between mr-4 mb-2">
        <header class="">
            Add a Library Octavo using an image or pdf copy
        </header>
    </div>

    {{-- INSTRUCTIONS --}}
    <div class="instructions border border-gray-300 text-sm m-2 p-2 rounded-lg shadow-lg">
        Use this form to add an octavo using:
        <ul>
            <li>An image file (png, jpg, jpeg), or</li>
            <li>pdf.</li>
        </ul>
        Note the following:
        <ul>
            <li>
                The file you upload should minimally contain the item's title and artists.
            </li>
            <li>The system recognizes the following types of library items:
                <ul>
                    <li>Octavo (this is the default if no type is found)</li>
                </ul>
            </li>
            <li>
                You will be returned to the Edit page when the file's information has been added.
            </li>
        </ul>
    </div>

    <div class="flex flex-col m-4">
        <form wire:submit="clickUploadImageOrPdf" class="flex flex-col border border-gray-300 p-2 rounded-lg">
            <input type="file" id="octavo" wire:model.blur="uploadedFileContainer" accept=".pdf,.png,.jpg,.gif,.jpeg" class="">
            <hint class="text-sm italic mb-2">Max file size: 4MB.</hint>
            <hint class="text-sm italic mb-2">This file size: {{ $fFileSize }}.</hint>
            <div class="text-sm text-red-500 mb-2">
                @if($uploadedMaxFileSizeExceeded)
                    {{ $uploadedMaxFileSizeExceededMessage }}
                @endif
            </div>
            @if($errors->any())
                <div id="error-box">
                    <!-- Display errors here -->
                </div>
            @endif

            @error('uploadedFileContainer')
                <span class="error text-red-500 text-sm">
                    @if($message === 'validation.uploaded') The file may be too large or the upload was interrupted. @else {{ $message }} @endif
                </span>
            @enderror
            @error('uploadedFileContainer.validation')<span class="error">Validation: {{ $message }}</span> @enderror
            @if($uploadedFileContainer)
                <button type="submit" wire:loading.attr="disabled"
                        class="bg-black text-white rounded-lg w-fit px-2 disabled:bg-gray-500"
                >
                    Upload
                </button>
            @else
                <div>
                    <button type="button" wire:loading.attr="disabled">
                        Waiting to upload file
                    </button>
                </div>
            @endif
            <div wire:loading wire:target="uploadedFileContainer" class="text-sm italic">
                Please wait while we prepare the file...
            </div>
        </form>
    </div>

</div>
