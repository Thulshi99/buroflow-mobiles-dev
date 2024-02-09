@props(['label' => 'Submit', 'loadingLabel' => 'Loading', 'colour' => 'indigo','icon' => null])
<button {{$attributes->merge(['class' => "inline-flex btn {$colour}"])}} wire:loading.attr="disabled" type="submit" >
    @if ($icon)
        <span class="mr-2">
            {!! $icon !!} <!-- Display the provided SVG icon -->
        </span>
    @endif
    <span wire:loading.remove>{{$label}}</span>
    <span wire:loading>{{$loadingLabel}}
        @isset($loader)
        {{$loader}}
        @else
        <x-loader class='inline-flex h-4 ml-3 align-middle' />
        @endisset
    </span>
</button>
