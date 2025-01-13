<div>

    <div id="recordings"
         class="flex flex-col md:flex-row md:ml-2 w-full space-y-1 sm:space-y-0 sm:space-x-1 justify-start mb-2 mt-4 border border-white border-t-gray-200">
        <h3>Reference Materials</h3>
        @foreach($referenceMaterials AS $pdf)
            <div class="text-blue-500 hover:underline">
                <a href="https://auditionsuite-production.s3.amazonaws.com/{{ $pdf['url'] }}" target="_blank">
                    {{ $pdf['descr'] }}
                </a>
            </div>
        @endforeach
    </div>
    <div class="flex flex-col md:ml-2 w-full space-y-1 sm:space-y-0 sm:space-x-1 justify-start ">
        @if($form->isMyStudent)
            <div>My student: {{ $form->isMyStudent }}.</div>
            <div class="text-xs italic">Scores will be averaged after all scores have been entered.</div>
        @else
            <span class="sr-only">Not my student</span>
        @endif
    </div>
</div>
