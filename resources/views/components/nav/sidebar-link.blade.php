@props(['link' => '#', 'label' => 'Link', 'isActive' => $link == request()->url()])

<a href="{{ $link }}" {{ $attributes }}
    @class([
        "flex items-center px-2 py-2 text-sm font-medium rounded-md group",
        "text-white bg-indigo-900" => $isActive,
        "text-indigo-100 hover:bg-indigo-600" => !$isActive ])
>
    @if (isset($icon))
    <div class="flex-shrink-0 w-6 h-6 mr-3 text-indigo-200">
        {{ $icon }}
    </div>
    @endif

    {{ $label }}

    {{ $slot }}

</a>
