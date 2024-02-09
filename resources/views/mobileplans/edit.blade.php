<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update Customer') }}
        </h2>
    </x-slot>

    <div class="mx-auto">
        <div class="bg-white shadow-xl rounded-xl">
            <livewire:mobileplans.edit-mobileplans :customer_id="$customer_id" :company_id="$company_id" />
        </div>
    </div>
</x-app-layout>