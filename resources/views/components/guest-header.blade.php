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
            right: 0rem;
        }
    }
</style>
<div class="bg-green-800">

    <div id="header" style="font-size:2.5rem; text-align: center;">
        <a href="/">
            TheDirectorsRoom.com
        </a>
    </div>

    <div id="links">

        <div style="margin-right: 0.5rem;">
            <a href="{{ route('register') }}"
               class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                Register
            </a>
        </div>


        <div style="margin-right: 0.5rem;">
            <a href="{{ route('login') }}"
               class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                Log in
            </a>
        </div>

    </div>
</div>
