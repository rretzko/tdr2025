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
            <tr>
                <th class="border border-t-transparent border-l-transparent"></th>
                @foreach($form->categories AS $category)
                    <th colspan="{{ $category->colSpan }}">{{ $category->descr }}</th>
                @endforeach
                <th class="border border-t-transparent border-r-transparent"></th>
            </tr>
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
                <tr>
                    <td>
                        {{ $scores['judgeName'] }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
