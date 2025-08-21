<div class=" w-1/2 p-2 rounded-lg">

    <div class="flex flex-col m-4">
        <div class="flex flex-col border border-gray-300 p-2 rounded-lg"
             wire:loading.class="pointer-events-none opacity-50">

            {{-- UPLOAD CONTAINER --}}
            <div class="flex flex-col">
                <input
                    type="file"
                    id="myFile"
                    wire:model="uploadedFileContainer"
                    accept=".doc, .docx, video/*, .pdf, .txt, audio/*, image/*, .png, .jpg, .jpeg, .xls, .xlsx, .csv"
                    class="">
                <hint class="text-sm italic mb-2">Max file size: 4MB.</hint>
                <div class="text-sm text-red-500 mb-2">
                    @if($uploadedMaxFileSizeExceeded)
                        {{ $uploadedMaxFileSizeExceededMessage }}
                    @endif
                </div>
                @error('uploadedFileContainer') <span class="error">{{ $message }}</span> @enderror
            </div>

            {{-- DESCRIPTION --}}
            <div class="flex flex-col w-5/6 mb-2">
                <label for="uploadDescr">
                    File Description<span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model="uploadDescr" placeholder="short description of upload file" required/>
                @error('uploadDescr') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            {{-- SHAREABLE --}}
            <div class="flex flex-row w-5/6 mb-2 space-x-2 items-center">
                <input type="checkbox" wire:model="form.shareable" id="shareable"/>
                <label for="shareable">Share with community</label>
            </div>

            <button type="button" wire:click="clickUploadDoc" wire:loading.attr="disabled"
                    class="bg-black text-white rounded-lg px-2 disabled:bg-gray-500"
            >
                Upload
            </button>
            <div wire:loading wire:target="uploadedFileContainer" class="text-sm italic">
                Please wait while we prepare the file...
            </div>
        </div>
        {{--    </div>--}}

    </div>
</div>
