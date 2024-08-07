<style>
    #links {
        display: flex;
        flex-direction: row;
        font-size: 0.8rem;
        justify-content: end;
        margin-right: 1rem;
    }

    @media screen and (min-width: 666px) {
        #links {
            position: absolute;
            top: 1.5rem;
            right: 0;
        }
    }
</style>
<div class="pt-2 ">

    <div class="hidden md:block absolute top-0 left-0 ">
        <x-application-logo width="80"/>
    </div>

    <div id="header" class="px-2 text-xl md:text-4xl text-left md:text-center mb-2 md:w-full">
        <a href="@if(auth()->user()) home @else / @endif">
            TheDirectorsRoom.com
        </a>
    </div>

    <div class="absolute top-0 right-0">
        <button
            class="inline-flex items-right border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-transparent dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <x-dropdown-link :href="route('logout')"
                                 onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                 class="text-right"
                >
                    <div class="hidden md:block">{{ __(Auth::user()->name) }}</div>
                    <div class="text-right">Log Out</div>
                </x-dropdown-link>
            </form>


        </button>
    </div>

</div>
