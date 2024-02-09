<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Orders') }}
        </h2>
        <div class="flex justify-end">
            <a class="btn indigo" type="submit" href="{{ route('mobileplans.assign')}}">
                <span>New Order</span>
            </a>
        </div>
    </x-slot>

    <div class="mx-auto">
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
            <livewire:orders.list-orders :order_status="$order_status"/>
        </div>
    </div>

</x-app-layout>
