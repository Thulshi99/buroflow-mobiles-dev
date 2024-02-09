<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create User') }}
        </h2>
    </x-slot>

    <div class="mx-auto">
        <div class="bg-white shadow-xl rounded-xl">
            <livewire:user-create-form />
        </div>
    </div>
</x-app-layout>