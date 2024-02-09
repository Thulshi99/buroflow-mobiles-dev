{{-- <x-app-layout>

    <div class="mx-auto">
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
            <livewire:customers.view-customer /> 
        </div>
    </div>

</x-app-layout> --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Customer') }}
        </h2>
    </x-slot>

    <div class="mx-auto">
        <div class="bg-white shadow-xl rounded-xl">
            <livewire:mobileplans.view-mobileplans :customer_id="$customer_id" :company_id="$company_id" />
        </div>
    </div>
</x-app-layout>

