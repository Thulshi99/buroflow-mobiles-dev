<div>
    <form class="p-6" wire:submit.prevent="submit">
        {{ $this->form }}
        <div class="flex justify-end py-6">
            <div class="mr-auto" wire:loading>
                Adding Data Topup to the Pool. Please wait...
            </div>
            <x-ui.forms.btn-load label="Submit" id="submit-button" loadingLabel='Submitting' disabled/>
        </div>
    </form>
</div>

{{-- Enable Submit Button Only if all Fields are Filled : START --}}
<script>
    const createButton = document.getElementById('submit-button');
    const formInputs = document.querySelectorAll('select[required]');

    formInputs.forEach(input => {
        input.addEventListener('input', () => {
            const allRequiredFieldsFilled = Array.from(formInputs).every(input => input.value.trim() !== '');
            createButton.disabled = !allRequiredFieldsFilled;
        });
    });
</script>
{{-- Enable Submit Button Only if all Fields are Filled : END --}}
