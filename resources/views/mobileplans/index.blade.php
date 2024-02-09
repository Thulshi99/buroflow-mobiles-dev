<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Mobile Plans') }}
        </h2>
        <div class="flex justify-end">

            <a class="btn indigo" type="submit" href="{{ route('mobileplans.create')}}">
                Add Plan
            </a>
        </div>
    </x-slot>

    <div class="mx-auto">
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
            <livewire:mobileplans.list-mobileplans />
        </div>
    </div>

</x-app-layout>
