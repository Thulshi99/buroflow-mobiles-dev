<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Qntrl Edit Order') }}
        </h2>
    </x-slot>
<!-- Create card form -->
<div class="body">
    <div class="table-responsive">
        {{-- @php
           dd( $card)
        @endphp --}}
        <form action="{{ route('simcard-qntrl.update') }}" method="POST">
            @csrf

            <input type="hidden" name="id" value="{{ $card['id'] }}">
            <input type="text" name="customfield_shorttext84" id="First" value="{{ $card['customfield_shorttext84'] }}"><br/>

            <input type="text" name="customfield_shorttext85" id="second" value="{{ $card['customfield_shorttext85'] }}"><br/>
            <input type="text" name="customfield_shorttext74" id="Possistion" value="{{ $card['customfield_shorttext74'] }}"><br/>
            <input type="text" name="customfield_shorttext79" id="Adress" value="{{ $card['customfield_shorttext79'] }}"><br/>
            <input type="text" name="customfield_shorttext73" id="Phone number" value="{{ $card['customfield_shorttext73'] }}"><br/>
           
            <button type="submit" class="btn primary">Update</button>
        </form>
    </div>
</div>
</x-app-layout>