<div id="coteacher" class="border border-transparent border-t-gray-200 pt-2 mb-8">
    <h3 class="text-yellow-100 font-semibold">Supervisory and CoTeacher(s)</h3>
    <div class="ml-2 flex flex-col">
        <p>
            Two areas that only display when you click on the edit button
            <img src="{{ Storage::disk('s3')->url('tutorials/schools/buttonEdit.png') }}" alt="Edit button"
                 class="inline mx-2"/>
            are:
        </p>
        <ol class="ml-8 list-decimal">
            <li>Supervisor and</li>
            <li>CoTeacher(s).</li>
        </ol>


        {{-- IMAGE --}}
        <div
            class="mt-2 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
            <div class="flex flex-col">
                <label>CoTeacher and Supervisor</label>
                <div id="coteacherAndSupervisorImage">
                    <img src="{{ Storage::disk('s3')->url('tutorials/schools/coteacherAndSupervisor.png') }}"
                         alt="Coteacher and supervisor fields">
                </div>
            </div>
        </div>

        {{-- SUPERVISORY --}}
        <div>
            <h4 class="font-semibold underline my-2">Supervisor</h4>
            <p>
                The supervisor fields are self-explanatory and optional but sometimes required
                for events managed by TheDirectorsRoom.com.
            </p>
            <ul class="ml-8 list-disc">
                <li>Supervisor Name</li>
                <li>Email</li>
                <li>Phone</li>
            </ul>
        </div>

        {{-- COTEACHER --}}
        <div>
            <h4 class="font-semibold underline my-2">CoTeacher(s)</h4>
        </div>
        <p class="mb-2">
            This section will display if there are other teachers registered on TheDirectorsRoom.com at
            your school. You can give that teacher access to your students, libraries, ensembles, events,
            and programs by clicking the checkbox next to that teacher's name.
        </p>
        <p class="mb-2">
            <u>Note: this is NOT reciprocal!</u> If you need access to <i>that</i> teacher's students, libraries,
            ensembles, events, and programs, then <i>that</i> teacher will need to click the checkbox next to
            <i>your</i> name on their Schools view.
        </p>

    </div>
</div>
