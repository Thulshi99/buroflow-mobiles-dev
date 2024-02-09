<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Qntrl Card Details') }}
        </h2>
        <br>
        <a href="/simcard-qntrl/index" class="btn primary">Go Back</a>
    </x-slot>

    <div class="mx-auto">
        <div class="bg-white shadow-xl rounded-xl">
            <div class="grid grid-cols-2 gap-3">
            
                <div class="px-3 py-4 prose">
                    <!-- ... Existing content ... -->

                    <h2>Customer Details</h2>
                    <b>layout ID:</b> {{ $card['layout_id'] }} <br>
                    <b>First name:</b> {{$card['customfield_shorttext84'] }} <br>
                    <b>Last name:</b> {{$card['customfield_shorttext85'] }} <br>
                    <b>Gender:</b> {{$card['customfield_shorttext86'] }} <br>
                    <b>Job Title:</b> {{$card['customfield_shorttext74'] }} <br>
                    <b>Birth day:</b> {{$card['customfield_shorttext75'] }} <br>
                    <b>Adress:</b> {{$card['customfield_shorttext79'] }} <br>
                    <b>email:</b> {{$card['customfield_longtext7'] }} <br>
                    <b>Phone number:</b> {{$card['customfield_shorttext73'] }} <br>

                    <!-- ... Add more fields as needed ... -->
                </div>
                <div class="px-3 py-4 prose">
                    <!-- ... Existing content ... -->

                    <h2>Card Details</h2>
                    <b>Team Name:</b> {{ $card['team_name'] }} <br>
                    <b>Order iID:</b> {{ $card['customfield_shorttext89'] }} <br>
                    <!-- ... Add more fields as needed ... -->

                    <h2>Plan Details</h2>
                    <!-- ... Add more fields as needed ... -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
