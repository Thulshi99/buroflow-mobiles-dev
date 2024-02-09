{{--
<x-jet-dropdown-link href="#" wire:click="deleteDatapool" onclick="confirm('Are you sure you want to delete this data pool?') || event.stopImmediatePropagation()" >
    {{ __('Delete Data Pool') }}
</x-jet-dropdown-link> --}}

<!-- Add Services Button -->
<a href="#">
    <button class="inline-flex btn indigo" style="justify-content: center; width: 250px; background-color: #c82a1b; color: white;" wire:loading.attr="disabled" wire:click="deleteDatapool" onclick="confirm('Are you sure you want to delete this data pool?') || event.stopImmediatePropagation()">
        <span>Delete Data Pool</span>
    </button>
</a>


