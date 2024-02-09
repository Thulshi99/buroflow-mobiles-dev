<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Search Location Address') }}
        </h2>
    </x-slot>


    <div class="pb-3 mx-auto">
        <div class="bg-white shadow-xl rounded-xl">
            <livewire:superloop.location-search />
        </div>
    </div>

</x-app-layout>
