<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Report Generation') }}
        </h2>
    </x-slot>

    <div class="mx-auto">
        <div class="bg-white shadow-xl rounded-xl">
            <livewire:reports.reportgeneration/>
        </div>
    </div>
    
</x-app-layout>