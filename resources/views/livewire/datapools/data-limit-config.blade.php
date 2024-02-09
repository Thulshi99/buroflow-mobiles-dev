<div>
    <form class="p-6" wire:submit.prevent="save">
        {{ $this->form }}
        <div class="flex justify-end py-6">
            <div class="mr-auto" wire:loading>
                Saving Data Limit Configurations. Please wait...
            </div>
            <x-ui.forms.btn-load label="Save" loadingLabel='Saving' id="save-button" disabled />
        </div>
    </form>
</div>

{{-- Enable Update Button Only if Field is Updated : START--}}
<script>
    const updateButton = document.getElementById('save-button');
    const formInputs = document.querySelectorAll('input, select, textarea');

    formInputs.forEach(input => {
        input.addEventListener('input', () => {
            updateButton.disabled = false; // Enable the button when input changes
        });
    });
</script>
{{-- Enable Update Button Only if Field is Updated : END --}}
