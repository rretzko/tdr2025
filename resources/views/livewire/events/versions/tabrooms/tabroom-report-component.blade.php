<div class="px-4">
    <h2>{{ ucwords($header) }}</h2>

    <x-pageInstructions.instructions instructions="{!! $pageInstructions !!}" firstTimer="{{ $firstTimer }}"/>

    <div id="container" class="space-y-2">

        {{-- SHOW/HIDE MENU DISPLAY BUTTON --}}
        <div class="mb-4 @if($displayReport) block @else hidden @endif"
        >
            <button type="button" wire:click="$toggle('displayReport')"
                    class="bg-green-100 ml-8 my-2 px-2 rounded-lg shadow-lg">
                Click to display reports menu...
            </button>
        </div>


        {{-- SHOW/HIDE REPORT CATEGORY MENU --}}
        <div @class([
            'block',
            'hidden' => $displayReport
        ])
        >
            <ol class="ml-12 list-decimal space-y-2 mb-4">
                <li>
                    <button
                        type="button"
                        wire:click="clickButton('byVoicePart')"
                        class="text-blue-500"
                    >
                        Audition Scores by voice part
                    </button>
                    <ul>
                        <li class="italic text-sm ">
                            View and download full audition scoring detail by voice part (personal identification
                            removed).
                        </li>
                    </ul>
                </li>
                <li>
                    <button
                        type="button"
                        wire:click="clickButton('allPrivate')"
                        class="text-blue-500"
                    >
                        Download Combined Audition Scores by voice part (private)
                    </button>
                    <ul>
                        <li class="italic text-sm ">
                            Download full audition scoring <b><i>including</i></b> personal identification.<br/>
                            NOTE: This is intended for event management use ONLY!
                        </li>
                    </ul>
                </li>
                <li>
                    <button
                        type="button"
                        wire:click="clickButton('allPublic')"
                        class="text-blue-500"
                    >
                        Download Combined Audition Scores by voice part (public)
                    </button>
                    <ul>
                        <li class="italic text-sm ">
                            Download full audition scoring detail (personal identification removed).<br/>
                            NOTE: This is intended for member-wide distribution.
                        </li>
                    </ul>
                </li>
                <li>Upload public scores
                    <ul>
                        <li>
                            Click here to upload the PUBLIC audition scores for inclusion in participating members'
                            results pages.
                        </li>
                    </ul>
                </li>
                <li>
                    <button
                        type="button"
                        wire:click="clickButton('ensembleParticipation')"
                        class="text-blue-500"
                    >
                        Ensemble participation report
                    </button>
                    <ul>
                        <li class="italic text-sm ">
                            View and download ensemble participation report.<br/>
                            NOTE: This csv file contains ALL contact information for the participants and their
                            directors. It is intended for need-to-know usage.
                        </li>
                    </ul>
                </li>
                <li>
                    <button
                        type="button"
                        wire:click="clickButton('seniorityParticipation')"
                        class="text-blue-500"
                    >
                        Student seniority report
                    </button>
                    <ul>
                        <li class="italic text-sm ">
                            View and download student participation across multiple events report.<br/>
                        </li>
                    </ul>
                </li>
            </ol>

        </div>
    </div>

    @if($displayReportData === 'byVoicePart')
        @include('components.forms.partials.tabrooms.reports.displayByVoicePart')
    @endif

    @if($displayReportData === 'allPrivate')
        @include('components.forms.partials.tabrooms.reports.allPrivate')
    @endif

    @if($displayReportData === 'allPublic')
        @include('components.forms.partials.tabrooms.reports.displayByVoicePart')
    @endif

    @if($displayReportData === 'ensembleParticipation')
        @include('components.forms.partials.tabrooms.reports.ensembleParticipation')
    @endif

    @if($displayReportData === 'seniorityParticipation')
        @include('components.forms.partials.tabrooms.reports.seniorityParticipation')
    @endif

</div>
