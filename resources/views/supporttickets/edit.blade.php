<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Support Ticket') }}
        </h2>
    </x-slot>

    <div class="mx-auto">
        <div class="bg-white shadow-xl rounded-xl">
            <livewire:supporttickets.edit-supportticket :support_ticket_id="$support_ticket_id"  />
        </div>
    </div>
</x-app-layout>
