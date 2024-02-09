<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Qntrl Card Details') }}
        </h2>
        <br>
        <a href="/qntrl/index" class="btn primary">Go Back</a>
    </x-slot>

    <div class="mx-auto">
        <div class="bg-white shadow-xl rounded-xl">
            <div class="grid grid-cols-2 gap-3">
                <div class="px-3 py-4 prose">
                    <p><b>Reference:</b> {{ $card['customfield_shorttext22'] }} <br>
                    <b>Qntrl Card:</b> {{ $card['id'] }}<br>
                    <b>Order Status:</b> {{ $card['customfield_dropdown4'] }} <br>
                    <b>Stage:</b> {{ $card['stage_name'] }} <br>
                    <b>Transfer Date:</b> {{ $card['due_date_hours'] }} <br>
                    <form action="{{ route('qntrl.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="cardId" value="{{ $card['id'] }}">
                        <input type="datetime-local" name="due_date">
                        <input type="submit" class= "btn success" value="Change Transfer Date">
                    </form>
                    <br>
                    <b>Order Submission Date:</b> {{ $card['created_date_hours'] }}</p>

                    <h2>Order Details</h2>
                    <b>LOCID:</b> {{ $card['customfield_shorttext14'] }} <br>
                    <b>NTD ID:</b> {{ $card['customfield_shorttext37'] }} <br>
                    <b>Copper Pair ID:</b> {{ $card['customfield_shorttext42'] }} <br>
                    <b>Previous ISP:</b> {{ $card['customfield_shorttext17'] }} <br>
                    <b>AAPT Response Code:</b> {{ $card['customfield_longtext2'] }}<br>
                    <b>AAPT Error Message:</b> {{ $card['customfield_shorttext32'] }}<br>
                    <b>AAPT Error Additional Info:</b> {{ $card['customfield_longtext3'] }}<br>
                    <b>AAPT Sales Order ID:</b> {{ $card['customfield_shorttext36'] }} <br>
                    <b>AAPT Service ID:</b> {{ $card['customfield_shorttext35'] }} <br>
                    <b>AAPT Product Order ID:</b> {{ $card['customfield_shorttext40'] }} <br>
                    <b>AAPT Product Order Activity:</b> {{ $card['customfield_shorttext43'] }} <br>
                    @if ($card['customfield_date6'])
                        <b>Order Completion Date:</b> {{ $card['customfield_date6_hours'] }} <br>
                    @else
                        <b>Order Completion Date:</b> {{ $card['customfield_date6_utc'] }} <br>
                    @endif
                </div>
                <div class="px-3 py-4 prose">
                    
                    @if ($card['customfield_dropdown4'] == 'Submitted')
                    <form action="{{ route('qntrl.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="cardId" value="{{ $card['id'] }}">
                        <input type="hidden" name="customfield_dropdown4" value="31914000000046150">
                        <input type="submit" class= "btn danger" value="Cancel Order">
                    </form>
                    @else
                    <button type="button" class="btn danger" disabled>Cancel Order</button>
                    @endif

                    <h2>Customer Order Details</h2>
                    <p>
                        <b>Customer Reference:</b> {{ $card['customfield_shorttext18'] }} <br>
                        <b>Customer Name:</b> {{ $card['customfield_shorttext21'] }} <br>
                        <b>Customer Retail Account Number:</b> {{ $card['customfield_shorttext16'] }} <br>
                        <b>Site Name:</b> {{ $card['customfield_shorttext34'] }} <br>
                        <b>NBN Speed:</b> {{ $card['customfield_dropdown6'] }} <br>
                        <b>Contract Term:</b> {{ $card['customfield_dropdown7'] }} <br>
                    </p> 
                    <h3>Radius Connection Details</h3>
                    <p>
                        <b>Username:</b> {{ $card['customfield_shorttext47'] }} <br>
                        <b>Password:</b> {{ $card['customfield_shorttext49'] }} <br>
                        <b>IP Address:</b> {{ $card['customfield_shorttext28'] }}
                    </p>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
