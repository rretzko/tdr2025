<div>
    <label class="font-semibold mb-2">Judge Summary Table</label>

    {{-- TABLE --}}
    <div>
        <style>
            #summaryTable {
                border-collapse: collapse;
            }

            #summaryTable td, th {
                border: 1px solid black;
            }
        </style>
        <table id="summaryTable" @class([
            "bg-red-100",
            'bg-white' => $form->scoreTolerance,
            'w-full' => $form->displayOnly,
            ])
        >
            <thead>

            {{-- CATEGORIES --}}
            <tr>
                <th class="border border-t-transparent border-l-transparent bg-gray-100"></th>
                @foreach($form->categories AS $category)
                    <th colspan="{{ $category->colSpan }}">{{ $category->descr }}</th>
                @endforeach
                <th class="border border-t-transparent border-r-transparent bg-gray-100"></th>
            </tr>

            {{-- FACTOR ABBRS --}}
            <tr>
                <th>name</th>
                @foreach($form->factors AS $factor)
                    <th style="width: 2rem;">{{ $factor->abbr }}</th>
                @endforeach
                <th>total</th>
            </tr>
            </thead>

            <tbody>
            @foreach($form->roomScores AS $scores)
                <tr @class([
                    "",
                    'font-semibold' => $scores['judgeUserId'] == auth()->id(),
                    ])
                >
                    <td class="px-1">
                        {{ $scores['judgeName'] }}
                    </td>
                    @forelse($scores['scores'] AS $score)
                        <td class="text-center">
                            {{ $score }}
                        </td>
                    @empty
                        <td colspan="{{ count($form->factors) }}" class="text-center">
                            no scores found
                        </td>
                    @endforelse

                    {{-- FILL IN ANY MISSING SCORE VALUES WITH BLANKS --}}
                    @php
                        $missingScores = ($this->form->factors->count() - count($scores['scores']));
                    @endphp

                    @for($i=0; $i < $missingScores; $i++)
                        <td></td>
                    @endfor

                    {{-- TOTAL SCORES --}}
                    <td class="text-center">
                        {{ array_sum($scores['scores']) }}
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>

        <div id="toleranceAdvisory">
            @if(! $form->scoreTolerance)
                <div class="text-red-600 font-semibold mt-2 ml-4">
                    SCORES ARE OUT OF {{ $form->roomTolerance }} POINT TOLERANCE!
                </div>
            @endif
        </div>

    </div>
</div>
