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
