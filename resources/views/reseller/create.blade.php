<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-application-logo />
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        
        <div class="mb-4 text-sm font-medium text-green-600">
            CREATE RESELLER
        </div>
        

        <form method="POST" action="{{ route('resellers.store') }}">
            @csrf
            <div>
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" class="block w-full mt-1" type="text" name="name" :value="old('name')" required autofocus />
            </div>
            <div>
                <x-jet-label for="domain" value="{{ __('Domain') }}" />
                <x-jet-input id="domain" class="block w-full mt-1" type="text" name="domain" :value="old('domain')" required autofocus />
            </div>
            <div>
                <x-jet-label for="reseller_id" value="{{ __('IMS Reseller ID') }}" />
                <x-jet-input id="reseller_id" class="block w-full mt-1" type="text" name="reseller_id" :value="old('reseller_id')" required autofocus />
            </div>
            <div>
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block w-full mt-1" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Retype-Password') }}" />
                <x-jet-input id="re-password" class="block w-full mt-1" type="password" name="re-password" required autocomplete="current-password" />
            </div>

            <!-- <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-jet-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div> -->

            <div class="flex items-center justify-end mt-4">
                <!-- @if (Route::has('password.request'))
                    <a class="text-sm text-gray-600 underline hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif -->

                <x-jet-button class="ml-4">
                    {{ __('CREATE RESELLER') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
