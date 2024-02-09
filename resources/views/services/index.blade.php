<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Services') }}
        </h2>
    </x-slot>

    <div class="mx-auto">
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
            <livewire:services.list-services />
        </div>
    </div>

</x-app-layout>
