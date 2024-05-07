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
<div class="pt-2">

    <div class="hidden md:block absolute top-0 left-0 ">
        <x-application-logo width="80"/>
    </div>

    <div id="header" class="px-2 text-xl md:text-4xl text-center mb-2 md:w-full">
        <a href="/">
            TheDirectorsRoom.com
        </a>
    </div>

    <div id="links">
        @include('layouts.navigation')
    </div>

</div>
