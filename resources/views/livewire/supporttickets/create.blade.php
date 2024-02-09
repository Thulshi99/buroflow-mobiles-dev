<div>
    <form class="p-6" wire:submit.prevent="submit">
        {{ $this->form }}
        <div class="flex justify-end py-6">
            <div class="mr-auto" wire:loading>
                Requesting Support. Please wait...
            </div>
            <x-ui.forms.btn-load label="Submit" loadingLabel='Submitting' />
        </div>
    </form>
</div>
