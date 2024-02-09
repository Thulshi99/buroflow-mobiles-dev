<div>
    {{-- @if(true) --}}
        <div class="modal">
            <!-- Modal content -->
            {{-- <h2>{{ $record->order_status }}</h2>
            <p>{{ $record->order_id }}</p> --}}
            <!-- Add other record fields as needed -->
            <button wire:click="$set('isOpen', false)">Close</button>
        </div>
    {{-- @endif --}}
</div>


