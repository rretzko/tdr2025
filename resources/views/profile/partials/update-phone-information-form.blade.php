<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Phone Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's phone information.") }}
        </p>
    </header>


    <form method="post" action="{{ route('profile.phoneUpdate') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="phoneMobile" :value="__('Mobile/Cell Phone')"/>
            <x-text-input id="phoneMobile" name="phoneMobile" type="text" class="mt-1 block w-full"
                          :value="old('phoneMobile', $user->phoneMobile())"/>
            <x-input-error class="mt-2" :messages="$errors->get('phoneMobile')"/>
        </div>

        <div>
            <x-input-label for="phoneWork" :value="__('Work Phone')"/>
            <x-text-input id="phoneWork" name="phoneWork" type="text" class="mt-1 block w-full"
                          :value="old('phoneMobile', $user->phoneWork())"/>
            <x-input-error class="mt-2" :messages="$errors->get('phoneWork')"/>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
