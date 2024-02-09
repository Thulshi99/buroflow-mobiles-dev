<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('SIM Cards') }}
        </h2>
        <div class="flex justify-end">
            <a class="btn btn-sm indigo" type="submit" href="{{ route('simcards.create')}}">
                <span>Add SIM Cards</span>
            </a>
        </div>
    </x-slot>

    <div class="mx-auto">
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
            <livewire:simcards.list-simcards />
        </div>
    </div>

</x-app-layout>
