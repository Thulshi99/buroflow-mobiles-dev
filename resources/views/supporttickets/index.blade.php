<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Support Tickets') }}
        </h2>
        <div class="flex justify-end">
            <a class="btn indigo" type="submit" href="{{ route('supporttickets.create')}}">
                <span>Create Support Tickets</span>
            </a>
        </div>
    </x-slot>

    <div class="mx-auto">
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
            <livewire:supporttickets.list-supporttickets />
        </div>
    </div>

</x-app-layout>
