<div>
    <form class="p-6" wire:submit.prevent="submit" enctype="multipart/form-data">
        {{ $this->form }}
        <div class="flex justify-end py-6">
            <div class="mr-auto" wire:loading>
               Creating. Please wait...
            </div>
            <x-ui.forms.btn-load label="Add Mobile Plan" loadingLabel='Creating' />
        </div>
    </form>
</div>
