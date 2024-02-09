<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Qntrl Create Order') }}
        </h2>
    </x-slot>
<!-- Create card form -->
<div class="class="mx-auto"">
    <div class="bg-white shadow-xl rounded-xl">
        <form class="p-6" action="{{ route('simcard-qntrl.store') }}" method="post">
            @csrf
            <input type="hidden" name="layout_id" value="1399000001544403">
            <input type="text" name="title" placeholder="Title"  id="customerReference">
            <input type="text" name="first_name" placeholder="First name"  id="customerReference">
            <input type="text" name="last_name" placeholder="Last name"  id="customerReference">
            <input type="text" name="phone_number" placeholder="Phone Number"  id="customerReference">
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
      
    </div>
</div>
</x-app-layout>