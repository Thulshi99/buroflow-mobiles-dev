<div>
    <form class="p-6" wire:submit.prevent="submit">
        {{$this->form}}
        <div class="flex justify-end py-6">
            <div class="mr-auto" wire:loading wire:target="submit">
                Processing. Please Wait...
            </div>
            <x-ui.forms.btn-load label="Submit" loadingLabel='Processing' wire:target="submit"/>
        </div>
    </form>
</div>

