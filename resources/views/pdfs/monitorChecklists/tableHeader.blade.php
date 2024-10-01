<table>
    <thead>

    {{-- CATEGORIES --}}
    <tr>
        <th colspan="3" class="headerTopLeft"></th>
        @foreach($room['scoreCategories'] AS $category)
            <th colspan="{{ $category['colSpan'] }}">
                {{ ucwords($category['descr']) }}
            </th>
        @endforeach
        <th colspan="2" class="headerTopRight"></th>
    </tr>

    {{-- FACTORS --}}
    <tr>
        <th>###</th>
        <th>Reg#</th>
        <th>VP</th>
        @foreach($room['scoreFactors'] AS $factor)
            <th style="min-width: 2rem; max-width: 2rem;">
                {{ strtolower($factor['abbr']) }}
            </th>
        @endforeach
        <th>Total</th>
        <th>Comments</th>
    </tr>
    </thead>
