<div>
    <form class="p-6" wire:submit.prevent="submit">
        {{ $this->form }}
        <div class="flex justify-end py-6">
            <div class="mr-auto" wire:loading>
                Creating Data Pool. Please wait...
            </div>
            <x-ui.forms.btn-load label="Create Data Pool" id="create-button" loadingLabel='Creating' disabled/>
        </div>
    </form>
</div>

{{-- Enable Create Button Only if all Fields are Filled : START --}}
<script>
    const createButton = document.getElementById('create-button');
    const formInputs = document.querySelectorAll('input[required], select[required], textarea[required]');

    formInputs.forEach(input => {
        input.addEventListener('input', () => {
            const allRequiredFieldsFilled = Array.from(formInputs).every(input => input.value.trim() !== '');
            createButton.disabled = !allRequiredFieldsFilled;
        });
    });
</script>
{{-- Enable Create Button Only if all Fields are Filled : END --}}
