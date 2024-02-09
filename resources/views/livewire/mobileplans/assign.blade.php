<div>
    @if (session()->has('errors'))
        <div class="alert alert-danger">
            @foreach (session('errors') as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <form class="p-6" wire:submit.prevent="submit">
        {{ $this->form }}
    </form>
</div>
