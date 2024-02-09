<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Qntrl Edit Order') }}
        </h2>
    </x-slot>
<!-- Create card form -->
<div class="body">
    <div class="table-responsive">
        <form action="{{ route('qntrl.update') }}" method="POST">
            @csrf

            <input type="hidden" name="id" value="{{ $card['id'] }}">
            <input type="hidden" name="layout_id" value="1399000000175001">
            <input type="hidden" name="record_owner" value="1399000000168005">
            <input type="hidden" name="team_id" value="1399000000027225">
            <label for="card-title">Title</label><br/>
            <input type="text" name="title" id="card-title" value="{{ $card['title'] }}"><br/>
            <label for="card-description">Description</label><br/>
            <input type="text" name="description" id="card-description" value="{{ $card['description'] }}"><br/>
            <label for="duedate">Date Due</label><br/>
            <input type="datetime" name="due_date_utc" id="due_date_utc" value="{{ $card['due_date'] }}"><br/>
            <label for="LOCID">LOC ID</label><br/>
            <input type="text" name="customfield_shorttext20" id="LOCID" value="{{ $card['customfield_shorttext20'] }}"><br/>
            {{-- <label for="carrier">Carrier</label><br/>
            <input type="text" name="customfield_shorttext25" id="carrier" value="{{ $card['customfield_shorttext25'] }}"><br/> --}}
            <label for="cpi">Copper Pair ID</label><br/>
            <input type="text" name="customfield_shorttext21" id="cpi" value="{{ $card['customfield_shorttext21'] }}"><br/>
            {{-- <label for="account">Account Number</label><br/>
            <input type="text" name="customfield_shorttext8" id="account" value="{{ $card['customfield_shorttext8'] }}"><br/><br> --}}
            <button type="submit" class="btn primary">Update</button>
        </form>
    </div>
</div>
</x-app-layout>