<div id="itemForm" class="mt-4 pt-2 border border-transparent border-t-gray-200 mb-12">
    <h3 class="text-yellow-100 font-semibold">Item Form</h3>
    <div class="ml-2 flex flex-col">
        <p class="flex flex-row items-center">
            Clicking the green plus button
            <img src="{{ Storage::disk('s3')->url('tutorials/libraries/plusButton.png') }}"
                 alt="Green Plus button image" class="mx-2"/>
            or indigo "Edit" button
            <img src="{{ Storage::disk('s3')->url('tutorials/libraries/editButton.png') }}"
                 alt="Indigo Edit button image" class="mx-2"/>
            from the Library Items page will display
            the following form.
        </p>
        <p class="my-2">
            Depending on the item type selected,
            (octavo, medley, book, digital, cd, dvd, cassette, vinyl)
            the system will automatically add or remove sections to
            accommodate the item's needs. The image below displays the Octavo input form, containing the
            most common fields for all item types except digital.
        </p>

        <x-tutorials.imageComponent
            alt="Octavo input form"
            id="octavoInputForm"
            label="Octavo Input Form"
            url="tutorials/libraries/octavoInputForm.png"
        />
    </div>

    <section id="fieldsSection">

        {{-- item type selector --}}
        <div id="itemTypeSelector" class="mt-4">
            <h3 class="font-semibold flex flex-row items-center space-x-2">
                <div class="text-yellow-200">Item Type Selector</div>
                <img src="{{ Storage::disk('s3')->url('tutorials/libraries/itemTypeSelector.png') }}"
                     alt="Item Type Selector bar"/>
            </h3>
            <p>
                Select the item type to be added by clicking the appropriate radio button.
            </p>
        </div>

        {{-- title --}}
        <div id="title" class="mt-4 pt-2 border border-transparent border-t-gray-300">
            <h3 class="font-semibold flex flex-row items-center space-x-2">
                <div class="text-yellow-200">Title</div>
                <img src="{{ Storage::disk('s3')->url('tutorials/libraries/fieldTitle.png') }}" alt="Title field"/>
            </h3>
            <p class="mt-2">
                Enter the name of the library item. All items <u>of the same item type</u> with matching titles
                will be displayed in the "Search Results" box on the right-hand side of the page.
                (Shown above are octavo library items matching "don" in the title.)
            </p>
            <p class="mt-2">
                Note that this search is across the system so that you can leverage input from other
                choral directors to save time and avoid having to retype information that is already
                in the system! Clicking on a link from the "Search Results" section will populate the form with known
                values and allow you to continue to update other fields.
            </p>
        </div>

        {{-- voicing --}}
        <div id="voicing" class="mt-4 pt-2 border border-transparent border-t-gray-300">
            <h3 class="font-semibold flex flex-row items-center space-x-2">
                <div class="text-yellow-200">Voicing</div>
                <img src="{{ Storage::disk('s3')->url('tutorials/libraries/fieldVoicing.png') }}" alt="Voicing field"/>
            </h3>
            <p class="mt-2">
                By entering a few letters, the system will automatically find and display the matching choices. You
                can either continue typing or click one of the displayed voicing to complete the field.
            </p>
        </div>

        {{-- available copies & current price--}}
        <div id="availableCopies" class="mt-4 pt-2 border border-transparent border-t-gray-300">
            <h3 class="font-semibold flex flex-row items-center space-x-2">
                <div class="text-yellow-200">Available Copies & Current Price</div>
                <img src="{{ Storage::disk('s3')->url('tutorials/libraries/fieldAvailableCopiesCurrentPrice.png') }}"
                     alt="Available copies and current price fields"/>
            </h3>
            <p class="mt-2">
                Available Copies: Enter the number of copies available in your library. Note that the number
                must be positive and greater than zero. The system will default to one.
            </p>

            <p class="mt-2">
                Current Price: Enter the price per item paid, <u>without the dollar sign</u>. Note this this
                field can be left blank.
            </p>
        </div>

        {{-- artists--}}
        <div id="artists" class="mt-4 pt-2 border border-transparent border-t-gray-300">
            <h3 class="font-semibold flex flex-row items-center space-x-2">
                <div class="text-yellow-200">Artists</div>
                <img src="{{ Storage::disk('s3')->url('tutorials/libraries/fieldArtists.png') }}" alt="artist fields"/>
            </h3>
            <p class="mt-2">
                Enter the artist's name(s) in the appropriate slot as described in the physical item
                (octavo, medley, book, etc.) If there are multiple artists for a single artist type,
                include all names separated by a comma with the final artist preceded by an "and".
                For example:
            </p>
            <ul class="ml-8 list-disc">
                <li>John Lennon and Paul McCartney</li>
                <li>Ben E. King, Jerry Lieber, and Mike Stoller</li>
            </ul>

            <p class="mt-2">
                By entering a few letters, the system will automatically find and display the matching choices
                (see "huf" above).
                You can either continue typing or click one of the displayed artist names to complete the field.
            </p>

            <p class="mt-2">
                Note in the example above that the composer, Greg Gilpin, is displayed but not editable.
                Editing is permitted if a) you have originally entered the value, and b) no other choral
                director is also using the value. In this case, Greg Gilpin is used by another choral director
                (i.e. has accepted the name as correctly spelled) and is not editable. Hovering over the
                question mark icon
                <img src="{{ Storage::disk('s3')->url('tutorials/libraries/questionMarkIcon.png') }}"
                     alt="Question mark icon" class="mx-2 inline align-middle"/>
                also describes this condition.
            </p>
        </div>

        {{-- tags--}}
        <div id="tags" class="mt-4 pt-2 border border-transparent border-t-gray-300">
            <h3 class="font-semibold flex flex-row items-center space-x-2">
                <div class="text-yellow-200">Tags</div>
                <img src="{{ Storage::disk('s3')->url('tutorials/libraries/fieldTags.png') }}" alt="tags field"/>
            </h3>
            <p class="mt-2">
                Consider the tags field as your option to attach any descriptors to the item as you find helpful.
                Think about words that you would use to search for this item, or other items <i>like</i> this item,
                if you didn't remember the specific title. Alternately, you can use tags to capture any values not
                otherwise captured in the application; instrumentation, for example.
            </p>

            <p class="mt-2">
                Tags need to separated by commas and are generally one word or short phrases.
            </p>

            <p class="mt-2">
                Remember that tags are shared across the system and will be seen and used by other choral directors.
            </p>
        </div>

        {{-- comments and rating--}}
        <div id="commentsAndRating" class="mt-4 pt-2 border border-transparent border-t-gray-300">
            <h3 class="font-semibold flex flex-row items-center space-x-2">
                <div class="text-yellow-200">Comments and Rating</div>
                <img src="{{ Storage::disk('s3')->url('tutorials/libraries/fieldCommentsAndRating.png') }}"
                     alt="comments and rating fields"/>
            </h3>
            <p class="mt-2">
                Consider this section as advise to your future-self.
                Five, ten, or fifteen years after initially performing this item, you might be tempted to
                do it again. What would be helpful to remember about the piece?
            </p>

            <p class="mt-2">
                Alternately, if another choral director was considering this piece, what advice would you give
                that choral director about your experience with the song? This section is designed to
                capture value for both of those situations.
            </p>

            <p class="mt-2">
                <b class="text-yellow-200">Rating</b>: This does not display when adding a new library item but will
                display on Editing
                the item and in the Programs application. Rate the item on a scale of 1-5, with 1 as "once and done"
                and 5 as "Every student should sing this!"
            </p>

            <p class="mt-2">
                Ratings are planned to be used in the future to give a global "score" of how choral directors
                rate this library item.
            </p>

            <p class="mt-2">
                <b class="text-yellow-200">Level and Difficulty</b>: From your subjective viewpoint, what is the choir's
                skill-level
                (elementary through professional) appropriate for this item and, within that skill level,
                how difficult is it (easy to hard)?
            </p>

            <p class="mt-2">
                <b class="text-yellow-200">Comments</b>: This is an open content field for reminders to your future-self
                and to other
                members of the choral community.
            </p>
        </div>

        {{-- locations --}}
        <div id="locations" class="mt-4 pt-2 border border-transparent border-t-gray-300">
            <h3 class="font-semibold flex flex-row items-center space-x-2">
                <div class="text-yellow-200">Locations</div>
                <img src="{{ Storage::disk('s3')->url('tutorials/libraries/fieldLocations.png') }}"
                     alt="locations fields"/>
            </h3>
            <p class="mt-2">
                Every good library is designed to make a stored item easy to find. That's the whole point: organizing
                a massive amount of similar looking items in a way that makes retrieval easy and accurate.
            </p>
            <p class="mt-2">
                <b class="text-yellow-200">Locations 1-3</b>: are provided to accommodate whatever system you
                are currently using by giving you three fields into which that system can be recorded. When played
                back, the system will report the fields as separated by a dash.
            </p>
            <p class="mt-2">
                For example: If your system is based on file cabinet number (ex. 3), file drawer number (ex. 2),
                and file id (ex: c), you could put each value into its respective box, which the system would
                return as 3-2-c.
            </p>
            <p class="mt-2">
                This location value would then be displayed on the library items table (under the type/location column)
                and on the pull sheets so that you will always be able to quickly locate the physical copy.
            </p>
            <p class="mt-2">
                If, for whatever reason, your current system isn't working for you, consider letting TDR Library simply
                assign a number to the item.<br/>
                <u>This happens automatically if you leave the location fields blank!</u>
            </p>
            <p class="mt-2">
                Regardless of which system you use, finding an item's location is as simple as using:
            </p>
            <ul class="ml-8 list-disc">
                <li>
                    TDR Library's search and filter functionalities to find the pieces or types of pieces you're looking
                    for,
                </li>
                <li>
                    Click the checkbox under the "pull" column,
                </li>
                <li>
                    Print the pull sheet using the pull-sheet link,
                </li>
                <li>
                    Pull each piece from your cabinets using the location code value.
                </li>
            </ul>
        </div>

        {{-- medley Selections --}}
        <div id="medleySelections" class="mt-4 pt-2 border border-transparent border-t-gray-300">
            <h3 class="font-semibold flex flex-row items-center space-x-2">
                <div class="text-yellow-200">Medley and Recordings Selections</div>
                <img src="{{ Storage::disk('s3')->url('tutorials/libraries/fieldMedleySelections.png') }}"
                     alt="medley selection fields"/>
            </h3>
            <p class="mt-2">
                Medley, CD, DVD, cassette, and vinyl input types have an additional section called "* Selections"
                to allow choral directors to document the individual songs contained within the medley or
                recording.
            </p>
            <p class="mt-2">
                After entering the title in the "Song 1" slot, more slots will appear to allow you to enter
                as many sub-selections as needed.
            </p>
        </div>

        {{-- medley Selections --}}
        <div id="digital" class="mt-4 pt-2 border border-transparent border-t-gray-300">
            <h3 class="font-semibold flex flex-row items-center space-x-2">
                <div class="text-yellow-200">Digital Items</div>
                <img src="{{ Storage::disk('s3')->url('tutorials/libraries/formDigital.png') }}" alt="digital form"/>
            </h3>
            <p class="mt-2">
                The digital item type allows you to upload digital files (pdfs, image files, docs, spreadsheets, etc.)
                for quick future reference and add web links to performances on the web (ex. youtube clips).
            </p>
            <p class="mt-2">
                Only the title field will initially display. Enter a title and then click an item from the
                Search Results on the right-hand side of the page. When a title is selected, the File Uploads
                and Web Links fields will display.
            </p>
            <p class="mt-2">
                After uploading, a document icon
                <img src="{{ Storage::disk('s3')->url('tutorials/libraries/docIcon.png') }}" alt="document icon"
                     class="inline mx-2"/>
                will display for each upload under the "docs" column and using the value entered in the
                "File Description" field as a label.
            </p>
            <p class="mt-2">
                After adding a new web address, a speaker icon
                <img src="{{ Storage::disk('s3')->url('tutorials/libraries/speakerIcon.png') }}" alt="speaker icon"
                     class="inline mx-2"/>
                will display for each web link under the "web" column and using the value entered in the
                "Label to identify link" field as a label.
            </p>
        </div>
    </section>
</div>
