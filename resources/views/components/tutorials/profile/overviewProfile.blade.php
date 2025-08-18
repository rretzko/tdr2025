<div id="overview" class="mb-8">
    <h3 class="text-yellow-100 font-semibold">Overview</h3>
    <div class="ml-2 flex flex-col">
        <div>
            TheDirectorsRoom.com Profile contains your contact and password information.
        </div>
        <div>
            <p>Here's what the Profile application can store:</p>
            <ul class="ml-4 list-disc">
                <li>Profile
                    <ul class="ml-8 list-disc text-sm">
                        <li>Your name</li>
                        <li>Your email address</li>
                        <li>Your mobile/cell and work phones</li>
                        <li>Your password changes</li>
                    </ul>
                </li>
            </ul>
        </div>
        <div id="profileForm"
             class="mt-2 p-2 flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 bg-gray-600 border border-gray-500">
            <div class="flex flex-col">
                <label>New Profile Form</label>
                <div id="newProfileFormImage">
                    <img src="{{ Storage::disk('s3')->url('tutorials/profile/emptyUserProfileForm.png') }}"
                         alt="New Profile Form">
                </div>
            </div>
        </div>
    </div>
</div>
