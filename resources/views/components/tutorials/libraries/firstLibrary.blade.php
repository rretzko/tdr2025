<div id="firstLibrary" class="mt-4 pt-2 border border-transparent border-t-gray-200 mb-12">
    <h3 class="text-yellow-100 font-semibold">Setting Up Your First Library</h3>
    <div class="ml-2 flex flex-col">
        <p>Clicking on the "Libraries" card from the Home page will automatically set up your first library, called
            "Home Library".</p>
        <p class="mt-2">This library is mandatory, cannot be removed, and is based on the presumption that all
            Choral
            Directors maintain some type of reference library at home with single/Director copies of the music
            that is being prepared at school. As described later under XXXXXX, you can instruct the system to
            <i>automatically</i> save a record in the Home Library every time you add a new item to the school
            library.
        </p>
        <div
            class="mt-2 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
            <div class="flex flex-col">
                <label>Libraries card on Home page</label>
                <div id="librariesCardImage">
                    <img src="{{ Storage::disk('s3')->url('tutorials/libraries/libraryCardFromHomePage.png') }}"
                         alt="Libraries card from home page">
                </div>
            </div>
            <div class="flex flex-col">
                <label>Home Library</label>
                <div id="homeLibraryImage">
                    <img src="{{ Storage::disk('s3')->url('tutorials/libraries/homeLibraryRow.png') }}"
                         alt="Home Library row">
                </div>
            </div>
        </div>
        <div class="mt-2">
            <h4 class="font-semibold underline">School Library</h4>
            <div>
                <p>Clicking the green plus-sign on the right side of the page will open a form to create
                    a new library; likely your school library.</p>
            </div>

            {{-- IMAGES --}}
            <div
                class="mt-2 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
                <div class="flex flex-col">
                    <label>New Library Form</label>
                    <div id="newLibraryImage">
                        <img src="{{ Storage::disk('s3')->url('tutorials/libraries/newSchoolLibraryForm.png') }}"
                             alt="Form for adding a new library">
                    </div>
                </div>
            </div>
            <div>
                <ol class="ml-8 mt-2 list-decimal">
                    <li>Enter the name of the library you are creating. Shorter is better, but be as descriptive as
                        needed.
                    </li>
                    <li>Select a school. The form will default to the "Home Library" value, but click the drop-down
                        box
                        to display any school that you have set up in the Schools application.
                    </li>
                    <li>The "Perusal Copies" section is where you will set up the default behavior for automatically
                        creating Home Library copies of any item added to this library.
                        <ul class="ml-4 list-disc text-sm">
                            <li>You can choose which type of library item you want to have automatically copied to
                                your
                                Home Library by clicking the appropriate check box, or leave them all blank if you
                                don't
                                wish to have any items automatically copied to your Home Library.
                            </li>
                            <li>
                                You can choose to have the system use the Item Id for its default location value, or
                                to
                                use whatever values you enter for the item's location in the School Library. A
                                fuller
                                description of the Location values is found below at XXXXX.
                            </li>
                            <li>
                                These default setting can be overridden on an item-by-item basis when entering
                                an item through a simple checkbox.
                            </li>
                        </ul>
                    </li>
                    <li>
                        The "Student Librarian" section will be automatically created after you save the form.
                        <ul class="ml-4 list-disc text-sm">
                            <li>
                                If you have a student librarian, the section will provide you with a fake email
                                address and random password for use by the student librarian. This will allow the
                                student librarian to log into TheDirectorsRoom.com with access to add and edit your
                                library items for this specific library but the student librarian will not be able
                                to
                                access any other parts of TheDirectorsRoom.com nor will they be able to remove any
                                items from this library.
                            </li>
                        </ul>
                    </li>
                </ol>
            </div>

            {{-- IMAGE --}}
            <div
                class="mt-2 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
                <div class="flex flex-col">
                    <label>Completed Library Form</label>
                    <div id="newLibraryImage">
                        <img src="{{ Storage::disk('s3')->url('tutorials/libraries/completedSchoolLibraryForm.png') }}"
                             alt="Completed Library form">
                    </div>
                </div>
            </div>

            <div class="ml-2 flex flex-col mt-2">
                <p>Clicking on the indigo "Edit" button from the Libraries page will display the image above.</p>
                <p>Clicking the Library name (ex. FJR School of Music Library) will display the table of
                    stored library items as displayed in the image below.
                </p>
            </div>

            {{-- IMAGE --}}
            <div
                class="mt-2 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
                <div class="flex flex-col">
                    <label>Edit button </label>
                    <div id="newLibraryImage">
                        <img src="{{ Storage::disk('s3')->url('tutorials/libraries/librariesTable.png') }}"
                             alt="Libraries table">
                    </div>
                </div>
                <div class="flex flex-col">
                    <label>Library Items table</label>
                    <div id="newLibraryImage">
                        <img src="{{ Storage::disk('s3')->url('tutorials/libraries/libraryItemsTable.png') }}"
                             alt="Library Items Table">
                    </div>
                </div>
            </div>
        </div>

        {{-- GLOBAL v. LOCAL --}}
        <div id="globalVLocal" class="mt-4">
            <h4 class="font-semibold underline mb-2">Global v. Local</h4>
            <p class="mb-2">
                <img src="{{ Storage::disk('s3')->url('tutorials/libraries/globalVLocal.png') }}"
                     alt="Global v. local button" class="inline"/>
            </p>
            <p class="mb-2">
                At the top of the page, you will see the label "Library:" with two option buttons;
                one with the name of your library and the second named "Global".
            </p>

            <p class="mb-2">
                The button with your library's name will display the items in your library.
            </p>
            <p class="mb-2">
                The "global" library is a unique feature of TheDirectorsRoom.com Library to provide you
                with a view into what other directors are using in their libraries and, by virtue of the
                number of times a piece is listed, the general popularity of a piece.
            </p>
            <p class="mb-2">
                The button with the Global name will display <u>ALL</u> of the items in the global
                library, including your library's items. The "global" display differs from your
                library's display as follows:
            </p>
            <ul class="ml-8 list-disc">
                <li>
                    A column titled "my" is added to the front of each row.
                    <ul class="ml-8 list-disc text-sm">
                        <li>Items from your library will be highlighted with an asterisk (*).</li>
                    </ul>
                </li>
                <li>
                    Your items will display in black font with all other items in a lighter shade
                    of gray.
                </li>
                <li>
                    The "perf" date will display, but ONLY your items will link to programs.
                </li>
                <li>
                    The "pull" column checkbox is only displayed on your items.
                </li>
                <li>
                    You are able to edit and remove your items, but these buttons
                    will be removed from items in other libraries.
                </li>
            </ul>

            <div
                class="mt-2 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
                <div class="flex flex-col">
                    <label>Global Library with "My" Selections Highlighted</label>
                    <div id="newLibraryImage">
                        <img src="{{ Storage::disk('s3')->url('tutorials/libraries/mySelections.png') }}"
                             alt="Global library items with local selections highlighted">
                    </div>
                </div>
            </div>
        </div>
    </div>{{-- end of firstLibrary content section --}}
</div> {{-- end of firstLibrary --}}
