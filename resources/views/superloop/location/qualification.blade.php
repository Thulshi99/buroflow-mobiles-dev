<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Qualify Location') }}
        </h2>
    </x-slot>


    <div class="mx-auto">
        <div class="bg-white shadow-xl rounded-xl">
            <livewire:superloop.location-qualification />
        </div>
    </div>

</x-app-layout>
