<div id="addRepertoire" class="border border-transparent border-t-gray-200 pt-2 mb-8">
    <h3 class="text-yellow-100 font-semibold">Adding Repertoire To A Program</h3>
    <div class="ml-2 ">
        Clicking the green "View" button <img src="{{ Storage::disk('s3')->url('tutorials/programs/viewButton.png') }}"
                                              alt="Green view button" class="mx-2 inline"/>
        from the Programs table opens a two-segment page with the
        program on the left-hand side of the page and repertoire form on the right-hand side of the page.
    </div>

    <div>

        {{-- IMAGE --}}
        <div
            class="mt-2 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
            <div class="flex flex-col">
                <label>Empty Program Repertoire View</label>
                <div id="emptyProgramRepertoireView">
                    <img src="{{ Storage::disk('s3')->url('tutorials/programs/programViewRepertoireEmpty.png') }}"
                         alt="Program view with empty repertoire">
                </div>
            </div>
        </div>

        <p class="my-2">
            Using the right-hand side of the view, three fields are available:
        </p>
        <ul class="ml-8 list-disc mb-2">
            <li>
                <span class="text-yellow-200">Select an Ensemble</span>: Choose an ensemble from the
                drop-down box.
            </li>
            <li>
                <span class="text-yellow-200">Performance Order</span>: Repertoire is ordered by
                ensemble/act, and then by the performance order.<br/>Performance order is continuous from
                the first song performed through the last.<br/>For example, if you perform 23 total songs
                in a concert, the performance order of the first song is one and the last is 23.
                <ul class="ml-8 list-disc text-sm">
                    <li>
                        Note: For your convenience, the system will automatically display the next number in the
                        sequence.
                    </li>
                </ul>
            </li>
            <li>
                <span class="text-yellow-200">Selection Title</span>: This field is essentially a "search"
                field; enter a few characters of the song title and the system will search you library to find
                repertoire matching those characters. Click a title to update the field.
                <ul class="ml-8 list-disc">
                    <li>
                        <img src="{{ Storage::disk('s3')->url('tutorials/programs/selectionTitleWithOptions.png') }}"
                             alt="Selection title with options displayed"/>
                    </li>
                </ul>
            </li>
            <li>
                When clicked, two actions will take place:
                <ol class="ml-8 list-decimal text-sm">
                    <li>The repertoire will display on the right-hand side of the page, and</li>
                    <li>
                        The left-hand side of the page will display in light-green background and
                        expand to display additional options detailed below.
                    </li>
                </ol>
            </li>
            <li>
                The full view now looks like this:
                <ul class="ml-8 list-disc">
                    <li>
                        <img src="{{ Storage::disk('s3')->url('tutorials/programs/fullFormEdit.png') }}"
                             alt="Displaying page with full full elements"/>
                    </li>
                </ul>
            </li>
            <li>
                <span class="text-yellow-200">Opener/Closer</span>: The system will automatically assign
                opener and closer status to the first/last song of the concert and each ensemble/act segment.
                You may optionally choose to check these fields as well.
            </li>
            <li>
                <span class="text-yellow-200">Addendums</span>: You will often want to highlight additional
                performance features like soloists, additional instrumentalists, or short program notes. You can
                use these fields to bring the highlights into the program (see example below).
            </li>
            <li>
                <span class="text-yellow-200">Comments and Ratings</span>
                Consider this section as advise to your future-self.
                Five, ten, or fifteen years after initially performing this item, you might be tempted to
                do it again. What would be helpful to remember about the piece?
                <br/>
                Alternately, if another choral director was considering this piece, what advice would you give
                that choral director about your experience with the song? This section is designed to
                capture value for both of those situations.
                <br/>
                Rate the item on a scale of 1-5, with 1 as "once and done" and 5 as "Every student should sing this!"
                <br/>
                Ratings are planned to be used in the future to give a global "score" of how choral directors
                rate this library item.
                <br/>
                <p class="mt-2">
                    <span class="text-yellow-200">Level and Difficulty</span>: From your subjective viewpoint, what is
                    the choir's
                    skill-level
                    (elementary through professional) appropriate for this item and, within that skill level,
                    how difficult is it (easy to hard)?
                </p>
                <p class="mt-2">
                    <span class="text-yellow-200">Comments</span>: This is an open content field for reminders to your
                    future-self
                    and to other
                    members of the choral community.
                </p>
            </li>
        </ul>

        {{-- IMAGE --}}
        <div
            class="mt-2 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
            <div class="flex flex-col">
                <label>Completed Full Form</label>
                <div id="completedFullForm">
                    <img src="{{ Storage::disk('s3')->url('tutorials/programs/completedFullForm.png') }}"
                         alt="Completed repertoire form">
                </div>
            </div>
            <div class="flex flex-col">
                <label>Program With One Selection</label>
                <div id="programViewOneRepertoire">
                    <img src="{{ Storage::disk('s3')->url('tutorials/programs/programViewOneRepertoire.png') }}"
                         alt="Program view with one selection">
                </div>
            </div>
        </div>

        <p class="my-2">
            Note that the song title on the left-hand side of the page is highlighted in
            <span class="text-blue-500">blue</span>.
            Clicking the title will return you to the full form to edit any values.
        </p>
    </div>
</div>
