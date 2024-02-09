<div>
    <form class="p-6" wire:submit.prevent="addressSearch">
        {{ $this->searchForm }}
        <div class="flex justify-end py-6">
            <div class="grow shrink-0">
                <p wire:loading wire:target='addressSearch'>
                    Searching for address...
                </p>
            </div>
            <div>
                <a class="btn secondary" wire:loading.target="addressSearch" wire:loading.attr="disabled"
                    wire:click.prevent='resetForm()'>Reset</a>
                <button class="ml-3 btn indigo" type="submit" 
                    wire:loading.target="addressSearch"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>Search Address</span>
                    <span wire:loading>Searching
                        <x-loader class='inline-flex h-4 ml-3 align-middle' />
                    </span>
                </button>
            </div>
        </div>
    </form>

    @if (count($this->locIds) >= 1)
        <form class="p-6" wire:submit.prevent="selectLocation">
            <div class="mb-6 panel purple">
                @php $locationStr = count($this->locIds) . " " . Illuminate\Support\Str::plural('location', $this->locIds);@endphp
                <strong class="text-purple-900">{{ $locationStr }} found!</strong><br>
                <p>Please select from one of the options below. Note that the field is searchable in large results.</p>
                {{ $this->locIdForm }}
                <div class="flex justify-end py-6">
                    <button class="btn indigo" type="submit" wire:loading.attr="disabled">
                        Select LocID
                    </button>
                </div>
            </div>
        </form>
    @endif
    <script defer>
        // Loads Address Finder for form
        (function() {
            var widget, initAddressFinder = function() {
                widget = new AddressFinder.Widget(
                    document.getElementById('streetName'),
                    'UF34MCPRN6YV9LDHAX8Q',
                    'AU', {
                        "address_params": {
                            "gnaf": "1"
                        },
                        "empty_content": "No matching address"
                    }
                );

                widget.on('address:select', function(fullAddress, metaData) {
                    // TODO - You will need to update these ids to match those in your form
                    const address = new Object();

                    address.streetNumber = document.getElementById('streetNumber').value = metaData
                        .street_number_1;
                    address.streetName = document.getElementById('streetName').value = metaData.street_name;
                    address.streetType = document.getElementById('streetType').value = metaData.street_type;
                    address.streetTypeSuffix = document.getElementById('streetTypeSuffix').value = metaData
                        .street_suffix;
                    address.suburb = document.getElementById('suburb').value = metaData.locality_name;
                    address.state = document.getElementById('state').value = metaData.state_territory;
                    address.postCode = document.getElementById('postCode').value = metaData.postcode;
                    // trigger livewire form update
                    Livewire.emit('autocompleted', address);
                });
            };

            function downloadAddressFinder() {
                var script = document.createElement('script');
                script.src = 'https://api.addressfinder.io/assets/v3/widget.js';
                script.async = true;
                script.onload = initAddressFinder;
                document.body.appendChild(script);
            };

            document.addEventListener('DOMContentLoaded', downloadAddressFinder);
        })();
    </script>

</div>
