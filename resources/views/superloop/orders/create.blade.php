<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Transfer NBN Service') }}
        </h2>
    </x-slot>


    <div class="mx-auto">
        <div class="bg-white shadow-xl rounded-xl">
        @if(request()->input('locId'))
            @if(\App\Models\User::all()[0]->current_team_id == 1)
            <livewire:superloop.orders.admin-create :locId="request()->input('locId')"/>
            @else
            <livewire:superloop.orders.create :locId="request()->input('locId')"/>
            @endif
        @else
            <div class="p-6">
                <h2 class="pb-4 text-xl font-semibold leading-tight text-gray-800">Service Qualification required.</h2>
                <p>A service qualification is required to start an order. To start a qualification you may <a class="font-semibold text-blue-500 underline hover:text-blue-800" href="{{route('sq.search')}}">search an address</a>.</p>
            </div>
        @endif
        </div>
    </div>

</x-app-layout>
