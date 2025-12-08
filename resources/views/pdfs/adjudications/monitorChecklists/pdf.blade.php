<div>

    @foreach($dto['rooms'] AS $room)

        {{--        @forelse($room['judges'] AS $judges)--}}

        {{--            @forelse($judges AS $judge)--}}
        @include('pdfs.adjudications.monitorChecklists.header')
        {{--                @include('pdfs.adjudications.monitorChecklists.judgeHeader')--}}
        <h3 style="text-align: center; font-size: 0.8rem; margin-top: -1rem;">Page
            1/{{ $room['pageCount'] }}</h3>
        @include('pdfs.adjudications.monitorChecklists.registrantsTable')
        @include('pdfs.adjudications.monitorChecklists.footer')

    @endforeach
</div>
