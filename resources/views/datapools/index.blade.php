<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Data Pools') }}
        </h2>
        <div class="flex justify-end">
            <a class="btn indigo" type="submit" href="{{ route('datapools.create')}}">
                <span>New Data Pool</span>
            </a>
        </div>
    </x-slot>

    <div class="mx-auto">
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
            <livewire:datapools.list-data-pools />
        </div>
    </div>

</x-app-layout>
