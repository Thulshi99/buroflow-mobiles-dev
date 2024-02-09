<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Retail Customers') }}
        </h2>
        <div class="flex justify-end">
            <a class="btn indigo" type="submit" href="{{ route('customers.create')}}">
                <span>Add Retail Customer</span>
            </a>
        </div>
    </x-slot>

    <div class="mx-auto">
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
            <livewire:customers.list-customers />
        </div>
    </div>

</x-app-layout>
