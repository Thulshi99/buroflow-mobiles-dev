<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Users') }}
        </h2>
        @if(\App\Models\User::find(auth()->user()->id)->tenant_role == 'admin')
        <div class="flex justify-end">
            <a class="btn indigo" type="submit" href="{{ route('users.create')}}">
                <span>Add User</span>
            </a>
        </div>
        @endif
    </x-slot>

    <div class="mx-auto">
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
            <livewire:list-users /> 
        </div>
    </div>

</x-app-layout>
