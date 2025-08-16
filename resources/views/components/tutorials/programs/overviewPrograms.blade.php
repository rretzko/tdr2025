<div id="overview" class="mb-8">
    <h3 class="text-yellow-100 font-semibold">Overview</h3>
    <div class="ml-2 flex flex-col">
        <div>
            TheDirectorsRoom.com Programs contains information about performance history, potentially creating
            a digital recap of your performances throughout your career.
        </div>
        <div class="mb-4">
            <p>Here's what the Programs application can store:</p>
            <ul class="ml-4 list-disc">
                <li>Programs
                    <ul class="ml-8 list-disc text-sm">
                        <li>Program title and subtitle</li>
                        <li>School</li>
                        <li>School year</li>
                        <li>Performance date</li>
                        <li>Song count</li>
                        <li>Tags for detailed searching</li>
                    </ul>
                </li>
                <li>
                    Ensemble or Act segmentation
                    <ul class="ml-8 list-disc text-sm">
                        <li>
                            Typically <b>Ensemble</b> segmentation is used for standard concerts where
                            each ensemble performs its own repertoire.
                        </li>
                        <li>
                            Typically <b>Act</b> segmentation is used for cabaret/solo performance
                            concerts where performances are individual efforts grouped into acts.
                        </li>
                    </ul>
                </li>
                <li>
                    Using items stored in the Libraries application,
                    <ul class="ml-8 list-disc text-sm">
                        <li>Title</li>
                        <li>Artists</li>
                    </ul>
                </li>
                <li>
                    Members
                    <ul class="ml-8 list-disc text-sm">
                        <li>
                            Roster of members grouped by school year and sorted by last name, first name.
                        </li>
                    </ul>
                </li>
                <li>
                    Search
                    <ul class="ml-8 list-disc text-sm">
                        <li>
                            Note the "Search" bar a the top of the page.
                            <br/>
                            You can enter any song title (surrounded by quotes), program title,
                            school year, or tag and the system will fetch that for you.
                            <br/>
                            Don't know when or how many times you've performed "Thank God I'm a Country Boy"?
                            <br/>
                            Just enter "Thank" in the search bar, click "Search" and let the system do the
                            work for you!
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        {{-- IMAGE --}}
        <div
            class="mt-2 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
            <div class="flex flex-col">
                <label>Programs Table</label>
                <div id="programsTable">
                    <img src="{{ Storage::disk('s3')->url('tutorials/programs/programsTable.png') }}"
                         alt="Programs table">
                </div>
            </div>

        </div>
    </div>
</div>
