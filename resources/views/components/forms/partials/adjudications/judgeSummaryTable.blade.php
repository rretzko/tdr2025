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
        <table id="summaryTable" class="">
            <thead>

            {{-- CATEGORIES --}}
            <tr>
                <th class="border border-t-transparent border-l-transparent"></th>
                @foreach($form->categories AS $category)
                    <th colspan="{{ $category->colSpan }}">{{ $category->descr }}</th>
                @endforeach
                <th class="border border-t-transparent border-r-transparent"></th>
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
                <tr class="font-semibold">
                    <td>
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
                    <td class="text-center">
                        {{ array_sum($scores['scores']) }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>
