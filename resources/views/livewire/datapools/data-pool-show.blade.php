<div>
    <form class="p-6" wire:submit.prevent="update">
        {{ $this->form }}
        <div class="flex justify-end py-6">
            <div class="mr-auto" wire:loading>
                Updating Data Pool Details. Please wait...
            </div>
            <x-ui.forms.btn-load label="Update" loadingLabel='Saving' id="update-button" disabled />
        </div>
    </form>
</div>

{{-- Enable Update Button Only if Field is Updated : START--}}
<script>
    const updateButton = document.getElementById('update-button');
    const formInputs = document.querySelectorAll('input, select, textarea');

    formInputs.forEach(input => {
        input.addEventListener('input', () => {
            updateButton.disabled = false; // Enable the button when input changes
        });
    });
</script>
{{-- Enable Update Button Only if Field is Updated : END --}}
