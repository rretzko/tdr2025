<x-layouts.pages00>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ \Diglactic\Breadcrumbs\Breadcrumbs::render( $dto['header']) }}
        </h2>
    </x-slot>

    <div class="py-0.5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 py-2 mb-4 overflow-hidden shadow-sm sm:rounded-lg p-4">
                <h2 class="font-semibold mb-2">Founder's Page</h2>
                <div class="border border-gray-400 p-2">
                    <form class="flex flex-col space-y-2" method="post" action="{{ route('founder.logInAs') }}">
                        @csrf
                        <label>Log In As</label>
                        <select name="user_id" class="w-fit" autofocus>
                            @foreach($dto['users'] AS $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->last_name }}, {{ $user->first_name }}
                                </option>
                            @endforeach
                        </select>
                        <input class="bg-gray-800 text-white w-fit px-2 rounded-full" type="submit" name="submit"
                               value="Submit"/>
                    </form>
                </div>
                {{-- DASHBOARD CARDS --}
                <div class="flex flex-col justify-center sm:flex-row sm:space-x-2 sm:flex-wrap items-center space-y-2">

                    @forelse($dto['cards'] AS $card)
                        <x-cards.dashboardCard
                            color="{{ $card['color'] }}"
                            descr="{!! $card['description'] !!}"
                            heroicon="{{ $card['heroicon'] }}"
                            href="{{ $card['href'] }}"
                            label="{{ $card['label'] }}"
                        />
                    @empty
                        <div>None Found.</div>
                    @endforelse

                </div>
                --}}
            </div>
        </div>
    </div>

</x-layouts.pages00>
