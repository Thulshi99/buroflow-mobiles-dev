<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Qntrl Create Order') }}
        </h2>
    </x-slot>
<!-- Create card form -->
<div class="class="mx-auto"">
    <div class="bg-white shadow-xl rounded-xl">
        <div class="filament-forms-text-input-component">
            <form class="p-6" action="{{ route('qntrl.create') }}" method="post">
                @csrf
                <input type="hidden" name="layout_id" value="1399000000175001">
                <input type="hidden" name="record_owner" value="1399000000168005">
                <input type="hidden" name="team_id" value="1399000000027225">
                <input type="hidden" name="title" value="  ">
                <label for="card-title">Customer Reference</label><br/>
                <input type="text" name="title" id="customerReference"><br/>
                <label for="card-description">Buroflow Reference</label><br/>
                <input type="text" name="description" id="buroflowReference"><br/>
                <label for="duedate">Date Due</label><br/>
                <input type="datetime" name="due_date_utc" id="due_date_utc"><br/>
                <label for="LOCID">LOC ID</label><br/>
                <input type="text" name="customfield_shorttext20" id="LOCID"><br/>
                {{-- <label for="carrier">Carrier</label><br/>
                <input type="text" name="customfield_shorttext25" id="carrier"><br/> --}}
                <label for="cpi">CPI</label><br/>
                <input type="text" name="customfield_shorttext21" id="cpi"><br/><br>
                {{-- <label for="account">Account Number</label><br/>
                <input type="text" name="customfield_shorttext8" id="account"><br/><br> --}}
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>
</x-app-layout>