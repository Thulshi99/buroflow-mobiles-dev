<div>
    <form class="p-6" wire:submit.prevent="submit">
        {{ $this->form }}
        <div class="flex justify-end py-6">
            <div class="mr-auto" wire:loading>
                Qualifying service location. Please wait...
            </div>
            <x-ui.forms.btn-load label="Qualify Location ID" loadingLabel='Qualifying' />
        </div>
    </form>
    @if (session()->has('message'))
        <div class="p-6">
            {{-- <div class="panel purple"> --}}
            @php $locId = data_get(session('message'), 'locationId') @endphp
            <h2 class="text-2xl font-bold text-purple-900">Service Qualification Results -- {{$locId}} </h2>
            <p class="p-6">
            <a href="{{ route('nbn.order.create', ['locId' => $locId]) }}" class="btn success">Create Order</a></p>
            @php $details = data_get(session('message'), 'details'); @endphp
            @php $raw = data_get(session('message'), 'raw'); @endphp

            <div class="grid grid-cols-2 pb-3 gap-x-3">
            @foreach ($details as $detail => $value)
                <div>
                    <strong class="text-purple-900">{{ $detail }}:</strong> {{ $value }}
                </div>
            @endforeach
            @if (data_get($raw, 'data.alternativeTechnology') == 'Fibre')
                <div>
                    <strong class="text-purple-900">Fibre Upgrade Available: </strong>
                    <x-svg.o-check-circle class="inline-flex h-4 text-green-600 place-self-center" />
                </div>
            @endif
            </div>

            <h3 class="text-xl font-semibold">Speeds Available</h3>
            @forelse (data_get($raw, 'data.availableProducts') as $name => $product)
            @unless (empty($product['options']))
            {{-- <strong>{{strtoupper($name)}}</strong> --}}
            <div class="grid grid-cols-6 gap-3 my-4">
                @foreach ($product['options'] as $option)
                    <div class="font-semibold text-center panel">{{$option}}</div>
                @endforeach
            </div>
            @endunless
            @endforeach
            
            {{-- nbn type cards --}}
            <h3 class="text-xl font-semibold">Infrastructures Present</h3>
            @forelse (data_get($raw, 'data.infrastructures') as $id => $infrastructure)
                @if (in_array(data_get($raw, 'data.technologyType'), ['FTTN', 'FTTC', 'FTTB']))
                    <x-sq.infrastructure.hybrid :raw="$raw" :item="$infrastructure" />
                @endif
                @if (in_array(data_get($raw, 'data.technologyType'), ['HFC']))
                    <x-sq.infrastructure.hfc :raw="$raw" :item="$infrastructure" />
                @endif
                @if (in_array(data_get($raw, 'data.technologyType'), ['FTTP']))
                    <x-sq.infrastructure.fttp :raw="$raw" :item="$infrastructure" />
                @endif
                @if (in_array(data_get($raw, 'data.technologyType'), ['FW']))
                    <x-sq.infrastructure.wireless :raw="$raw" :item="$infrastructure" />
                @endif
            @empty
                <div class="mt-2 panel bg-slate-500/10">
                    <h3 class="text-lg font-semibold text-slate-700">Not found</h3>
                    No infrastructure currently present for this technology type.
                </div>
            @endforelse
            {{-- /nbn type cards --}}
            {{-- </div> --}}
        </div>
        @env('local')
        <div>
            @dump(json_encode(data_get(session('message'), 'raw')))
            <p>&nbsp;</p>
        </div>
        @endenv
    @endif
</div>
