<div id="libraryItems" class="mt-4 pt-2 border border-transparent border-t-gray-200 mb-12">
    <h3 class="text-yellow-100 font-semibold">Library Items</h3>
    <div class="ml-2 flex flex-col">
        <p>Clicking on the indigo "Edit" button from the Libraries (see image above) page will display
            the following image.
        </p>

        <x-tutorials.imageComponent
            alt="Empty Library Items table"
            id="emptyLibraryItemsTable"
            label="Empty library items table"
            url="tutorials/libraries/emptyLibraryItemsTable.png"
        />

        <h4 class="font-semibold underline">Navigation</h4>
        <p class="mt-2">
            Before diving into the table, let's talk about the information <i>above</i> the table:
        </p>
        <ol class="ml-4 mt-2 list-decimal">

            {{-- SEARCH BAR --}}
            <li>Search Bar
                <ul class="ml-4">
                    <li>Found at the top of the section with the placeholder "Search title, artist name or tag",
                        you can use the search bar to quickly scan your list of library items to find a
                        specific item using a portion of the item's title, artist name, or tag value.
                        <ul class="ml-8 list-disc text-sm">
                            <li>
                                "Artist name" includes any composer, arranger, words-and-music, words,
                                music, choreographer, or author's name.
                            </li>
                            <li>As an example, entering "don" (without the quotes) in the Search bar would display the
                                library items in the image below (if you had these in your library, of course):
                                <x-tutorials.imageComponent
                                    alt="search for don results"
                                    id="searchForDon"
                                    label="Library Item results after search for 'don'"
                                    url="tutorials/libraries/libraryItemSearchForDon.png"
                                />
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>

            {{-- DOWNLOAD BUTTON --}}
            <li class="mt-2">
                <div class="flex flex-row">
                    Download Button
                    <span class="ml-2">
                        <img src="{{ Storage::disk('s3')->url('tutorials/libraries/downloadButton.png') }}">
                    </span>
                </div>
                <ul>
                    <li>
                        Located across from the Search bar, click this button to download your <i>entire</i>
                        library with all details into a csv file.
                    </li>
                    <li>
                        You can use this for backup purposes or to perform more specialized analysis as needed.
                    </li>
                </ul>
            </li>

            {{-- FILTERS --}}
            <li class="mt-2">
                <div class="flex flex-row">
                    Table Filters
                    <span class="ml-2">
                        <img src="{{ Storage::disk('s3')->url('tutorials/libraries/filters.png') }}"/>
                    </span>
                </div>
                <ul>
                    <li>
                        Found below the Search bar, there are two filters identified by drop-down boxes
                        defaulting to the word "ALL":
                        <ul class="ml-8 list-disc text-sm">
                            <li>Voicing Filter: returns all library items for the selected voicing choice
                                (ex. SATB, TTBB, etc.)
                            </li>
                            <li>Type Filter: returns all library items for the selected type choice
                                (ex. Octavo, medley, book, etc.)
                                <ul class="ml-8 list-disc text-sm">
                                    <li>Note that there are type grouping for Paper (octave, medley, and book),
                                        and Recordings (digital, cd, dvd, cassette, and vinyl).
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>

            {{-- TABLE COLUMNS --}}
            <li class="mt-2">
                Table Columns
                <ul class="ml-8 list-disc">
                    <li>Column headers type/location, title, and voicing are highlighted in
                        <span class="text-blue-500">blue</span> and
                        can be clicked to sort the table by that column.
                    </li>
                    <li>
                        "Pull Sheet" is a link that displays a pdf of pull selections; more on that below.
                    </li>

                    {{-- ### --}}
                    <li>
                        <span class="font-semibold text-yellow-200">###</span>: Simply a row number.
                    </li>

                    {{-- location/type --}}
                    <li>
                        <span class="font-semibold text-yellow-200">type/location</span>: Displays the type of the
                        library
                        item and it's location code. (More on the location code below.)
                        <ul class="ml-8 text-sm list-disc">
                            <li>Item types are: octavo, medley, book, digital, cd, dvd, cassette, and vinyl.</li>
                        </ul>
                    </li>

                    {{-- title --}}
                    <li>
                        <span class="font-semibold text-yellow-200">title</span>: Displays the title of
                        the library item. Note that all titles are automatically formatted as a title with all
                        words capitalized.
                        <ul class="ml-8 text-sm list-disc">
                            <li>Note also that items containing multiple songs (medleys, music books, recordings),
                                all entered songs will be displayed here <u>AND</u> will be included
                                in any search results.
                            </li>
                            <li>
                                As an example, "Seventies Gold Medley" is included in the search results for "don"
                                because a song in the medley, Your Mama Don't Dance, includes the fragment "don".
                            </li>
                        </ul>
                    </li>

                    {{-- count --}}
                    <li>
                        <span class="font-semibold text-yellow-200">count</span>: Displays the count of items
                        available in your library.
                    </li>

                    {{-- artists --}}
                    <li>
                        <span class="font-semibold text-yellow-200">artists</span>: Displays the artists associated
                        with the library item.
                        <ul class="ml-8 text-sm list-disc">
                            <li>Artists are: composer, arranger, words-and-music, words, music, choreographer, and
                                author.
                            </li>
                        </ul>
                    </li>

                    {{-- voicings --}}
                    <li>
                        <span class="font-semibold text-yellow-200">voicings</span>: Displays the voicing
                        (SATB, SSA, TTBB, etc.) of the library item.
                        <ul class="ml-8 text-sm list-disc">
                            <li>
                                If the item has no voicing (ex. text book) or multiple voicings (ex. recording),
                                the system will default to "none" or "various" may be selected.
                            </li>
                        </ul>
                    </li>

                    {{-- tags --}}
                    <li>
                        <span class="font-semibold text-yellow-200">tags</span>: Displays all tags that have been
                        used for the library item.
                        <ul class="ml-8 text-sm list-disc">
                            <li>
                                Note that this will include tags entered by other choral directors so that you might
                                receive additional benefit of the collective mind.
                            </li>
                        </ul>
                    </li>

                    {{-- docs --}}
                    <li>
                        <div class="flex flex-row">
                            <span class="font-semibold text-yellow-200">docs</span>:
                            Displays a clickable document icon
                            <img src="{{ Storage::disk('s3')->url('tutorials/libraries/docIcon.png') }}" class="mx-2"/>
                            that opens the referenced document.
                        </div>
                        <ul class="ml-8 text-sm list-disc">
                            <li>
                                Note: Documents can be any digital asset up to 4Mb in size. This includes pdfs,
                                docs, spreadsheets, images, small sound files, etc.
                            </li>
                            <li>
                                Hovering over the doc icon will display the label provided for the document.
                            </li>
                        </ul>
                    </li>

                    {{-- web --}}
                    <li>
                        <div class="flex flex-row">
                            <span class="font-semibold text-yellow-200">web</span>:
                            Displays a clickable speaker icon
                            <img src="{{ Storage::disk('s3')->url('tutorials/libraries/speakerIcon.png') }}"
                                 class="mx-2"/>
                            that opens a tab to the referenced web link.
                        </div>
                        <ul class="ml-8 text-sm list-disc">
                            <li>
                                For example: A link to a YouTube performance.
                            </li>
                            <li>
                                Hovering over the speaker icon will display the label provided for the link.
                            </li>
                        </ul>
                    </li>

                    {{-- perf --}}
                    <li>
                        <span class="font-semibold text-yellow-200">perf</span>:
                        If you have created programs using the Programs application,
                        this column displays clickable date(s) for every program where this item is
                        performed.
                        <ul class="ml-8 text-sm list-disc">
                            <li>
                                Clicking the date will take you to that program.
                            </li>
                        </ul>
                    </li>

                    {{-- pull --}}
                    <li>
                        <span class="font-semibold text-yellow-200">pull</span>:
                        Click the checkbox under the "pull" column to create a pull sheet for researching
                        multiple library items. The pull sheet will include key information for finding
                        the item in your library.
                        <ul class="ml-8 text-sm list-disc">
                            <li>
                                Click the "Pull Sheet" link above the "Edit" and "Remove" buttons to
                                open the pull sheet pdf.
                            </li>
                            <li>
                                <img src="{{ Storage::disk('s3')->url('tutorials/libraries/pullSheetExample.png') }}"
                                     alt="pull sheet example"/>
                            </li>
                        </ul>
                    </li>

                    {{-- buttons --}}
                    <li>
                        <span class="font-semibold text-yellow-200">Edit</span> and <span
                            class="font-semibold text-yellow-200">Remove</span> buttons:
                        Click these buttons to, respectively, Edit or Remove the item from your library.
                        <ul class="ml-8 text-sm list-disc">
                            <li>
                                Note: The "remove" button will ALWAYS display an "Are you sure?" check to ensure
                                that you <i>meant</i> to click that button!
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ol>
    </div>
</div>
