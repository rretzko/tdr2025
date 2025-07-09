<div class="border border-gray-400 p-2 mt-2">
    <div class="font-bold mb-2">
        Optional Location
        <span class="font-normal text-xs italic ">
            If you use your own filing system, use the fields below to identify
            where this library item can be found.
        </span>
    </div>
    <div class="">
        @if((! isset($form->policies['canEdit']['location'])) || $form->policies['canEdit']['location'])
            <div class="flex flex-col space-y-2">
                <label class="flex flex-row">
                    <div class="w-24">Location 1</div>
                    <input type="text" wire:model="form.locations.0" id="location0" placeholder="ex. File cabinet #"/>
                    <hint class="flex ml-2 mt-2 text-sm middle h-full" id="locationDisplay">hint</hint>
                </label>

                <label class="flex flex-row">
                    <div class="w-24">Location 2</div>
                    <input type="text" wire:model="form.locations.1" id="location1" placeholder="ex. File drawer #"/>
                </label>

                <label class="flex flex-row">
                    <div class="w-24">Location 3</div>
                    <input type="text" wire:model="form.locations.2" id="location2" placeholder="ex. File id"/>
                </label>
            </div>
        @endif
    </div>

    <script>
        // Select inputs and display element
        const location0 = document.getElementById('location0');
        const location1 = document.getElementById('location1');
        const location2 = document.getElementById('location2');
        const locationDisplay = document.getElementById('locationDisplay');

        function updateLocationDisplay() {
            const val0 = location0.value.trim();
            const val1 = location1.value.trim();
            const val2 = location2.value.trim();

            // Join non-empty values with hyphens
            const parts = [val0, val1, val2].filter(v => v !== '');
            locationDisplay.textContent = parts.join('-');
        }

        // Add event listeners to update display on input
        [location0, location1, location2].forEach(input => {
            input.addEventListener('input', updateLocationDisplay);
        });

        // Initialize display on page load
        updateLocationDisplay();
    </script>
</div>
