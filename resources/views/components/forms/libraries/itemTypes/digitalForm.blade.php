<div>
    @php($moduleName="digital library items")

    {{-- TITLE --}}
    <div class="mb-4">
        @php($placeholder="To begin, enter a title & then select from Search Results")
        @include('components.forms.elements.livewire.libraryItem.title')
    </div>

    {{-- FILE UPLOADS --}}
    <div>
        <h3 class="font-semibold my-2">File Uploads</h3>
        <div class="ml-4">
            @if($form->digitalUrls)
                {{-- display form if a title has been entered --}}
                @include('components.forms.elements.livewire.libraryItem.uploadLibraryItemDoc')
            @endif
        </div>
    </div>

    {{-- PREVIOUS FILE UPLOADS --}}
    @if(count($form->previousFileUploads))
        <div id="previousFileUploads" class="border border-transparent border-t-white pt-2 mb-4">
            <h3 class="font-semibold">Previous File Uploads</h3>
            <table class="ml-8 border-collapse">
                <thead>
                <tr>
                    <th class="px-2 border border-gray-400">Description</th>
                    <th class="px-2 border border-gray-400">Share?</th>
                    <th class="px-2 border border-gray-400 sr-only">Remove</th>
                </tr>
                </thead>
                <tbody>
                @foreach($form->previousFileUploads AS $doc)
                    <tr>
                        <td class="px-2 border border-gray-400">{{ $doc['label'] }}</td>
                        <td class="px-2 border border-gray-400 text-center">
                            <input type="checkbox"
                                   wire:click="updateShareable({{ $doc['id'] }})" @checked($doc['shareable'])/>
                        </td>
                        <td class="px-2 border border-gray-400">
                            <button
                                type="button"
                                @class([
                                    "px-2 text-sm bg-red-500 text-white rounded-lg",
                                    'hidden' => ! auth()->user()->isTeacher(),
                                ])
                                wire:confirm="Are you sure you want to remove this doc?"
                                wire:click="removeDoc({{ $doc['id'] }})"
                                @disabled(! auth()->user()->isTeacher())
                            >
                                Remove
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- WEB LINKS --}}
    <div class="pt-2 border border-transparent border-t-gray-500">
        <h3 class="font-semibold mb-2">Web Links</h3>
        <div class="ml-4">
            {{-- ADVISORY --}}
            <div class="border border-gray-700 p-2 text-sm bg-gray-200 w-5/6 mx-auto mb-2">
                <h3>Please note the following:</h3>
                <ul>
                    <li>Links are shared.</li>
                    <li>Only links entered by you are editable by you.</li>
                    <li>Links are to be used SOLELY for educational purposes.</li>
                    <li>All pertinent copy right laws will be followed.</li>
                    <li>Links MUST NOT include any inappropriate materials.</li>
                </ul>
            </div>

            {{-- DIGITAL URL --}}
            @foreach($form->digitalUrls AS $libDigital)

                <div class="flex flex-row space-x-2 mt-2">
                    {{-- URL --}}
                    <div class="flex flex-col w-1/2 ">
                        <label for="digitalUrl" class="ml-8">
                            Web Address
                        </label>
                        <div class="flex space-x-2">
                            <div class="flex items-center w-6">
                                {{ $loop->iteration }}
                            </div>
                            <input
                                type="url"
                                wire:model="form.digitalUrls.{{ $loop->index }}.url"
                                class="w-5/6"
                                placeholder="@if($form->sysId == 0) Enter title above to select a library item from search results.. @endif"
                                @disabled(($form->sysId == 0) )
                            />

                            @error('form.digitalUrl')
                            <x-input-error messages="{{ $message }}" aria-live="polite"/>
                            @enderror
                        </div>
                    </div>

                    {{-- DIGITAL URL LABEL--}}
                    <div class="flex flex-col w-1/2 ">
                        <label for="digitalUrl">
                            Label to identify link
                        </label>
                        <input
                            type="text"
                            wire:model="form.digitalUrls.{{ $loop->index }}.label"
                            class="w-11/12"
                            placeholder="@if($form->sysId)Enter label to identify this link on mouse-hover... @endif"
                            @disabled($form->sysId == 0)
                        />
                        @error('form.digitalUrlLabel')
                        <x-input-error messages="{{ $message }}" aria-live="polite"/>
                        @enderror
                    </div>
                </div>
            @endforeach
        </div>
    </div> {{-- end of web links --}}

</div>
