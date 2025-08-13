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
    <x-tutorials.libraries.firstLibrary/>

    {{-- LIBRARY ITEMS --}}
    <x-tutorials.libraries.libraryItems/>

    {{-- ITEM FORM --}}
    <x-tutorials.libraries.itemForm/>


</x-layouts.tutorial>
