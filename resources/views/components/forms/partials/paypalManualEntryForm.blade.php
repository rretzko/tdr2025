<form class="flex flex-col space-y-2" method="post" action="{{ route('founder.paypalManualEntry') }}">
    @csrf
    <label>PayPal Manual Entry</label>
    <input class="px-2" type="text" name="paypalData" value=""/>
    <input class="bg-gray-800 text-white w-fit px-2 rounded-full" type="submit" name="submit"
           value="Submit"/>

    @session('success')
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 3000)"
        x-transition:leave="transition ease-in duration-500"
        class="ml-2 text-green-600 italic"
    >
        {{ session('success') }}
    </div>
    @endsession


    @if($errors->any())
        <div class="text-red-600 italic">
            {{ $errors->first() }}
        </div>
    @endif
</form>
