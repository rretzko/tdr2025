<x-layouts.tutorial>
    <x-slot name="header">Libraries Tutorial</x-slot>
    <x-tabs.tutorialsLibrariesTabs/>

    {{-- Overview --}}
    <div id="overview">
        <h3 class="text-yellow-100 font-semibold">Overview</h3>
        <div class="ml-2 flex flex-col">
            <div>
                TheDirectorsRoom.com Library is a storage container of digital information about your physical library.
            </div>
            <div>
                <p>Here's what the Library can store:</p>
                <ul class="ml-4 list-disc">
                    <li>Paper
                        <ul class="ml-4 list-disc text-sm">
                            <li>Octavos</li>
                            <li>Medleys</li>
                            <li>Text Books</li>
                            <li>Music Books</li>
                        </ul>
                    </li>
                    <li>Digital Records
                        <ul class="ml-4 list-disc text-sm">
                            <li>links to YouTube and other clips</li>
                            <li>uploaded pdfs, docs, images, etc.</li>
                        </ul>
                    </li>
                    <li>Recordings
                        <ul class="ml-4 list-disc text-sm">
                            <li>Cds</li>
                            <li>Dvds</li>
                            <li>Cassettes</li>
                            <li>Vinyl</li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- FIRST LIBRARY --}}
    <div id="firstLibrary" class="mt-4 pt-2 border border-transparent border-t-gray-200 mb-12">
        <h3 class="text-yellow-100 font-semibold">Setting Up Your First Library</h3>
        <div class="ml-2 flex flex-col">
            <p>Clicking on the "Library" card from the Home page will automatically set up your first library, called
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
                    <label>Library card on Home page</label>
                    <div id="libraryCardImage">
                        <img src="{{ Storage::disk('s3')->url('tutorials/libraries/libraryCardFromHomePage.png') }}"
                             alt="Library card from home page">
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
                <div
                    class="mt-2 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
                    <div class="flex flex-col">
                        <label>New Library Form</label>
                        <div id="newLibraryImage">
                            <img src="{{ Storage::disk('s3')->url('tutorials/libraries/newSchoolLibraryForm.png') }}"
                                 alt="Library card from home page">
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
            </div>
        </div>{{-- end of firstLibrary content section --}}
    </div> {{-- end of firstLibrary --}}


</x-layouts.tutorial>
