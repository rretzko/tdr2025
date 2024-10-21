<div id="recordings"
     class="flex flex-col md:flex-row md:ml-2 w-full space-y-1 sm:space-y-0 sm:space-x-1 justify-start rounded-lg mb-2">
    @foreach($form->recordings AS $type => $url)
        <div class="flex flex-col text-white px-1 border border-r-gray-600 rounded-lg bg-gray-800">
            <label class="text-center">{{ $type }}</label>
            <audio id="audioPlayer-{{ $type }}" class="mx-auto" controls
                   style="display: block; justify-self: start; margin-bottom: 0.50rem;">
                <source id="audioSource-{{ $type }}"
                        src="https://auditionsuite-production.s3.amazonaws.com/{{ $url }}"
                        type="audio/mpeg"
                >
                " Your browser does not support the audio element. "
            </audio>
        </div>
    @endforeach
</div>
