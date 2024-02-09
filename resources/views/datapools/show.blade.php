<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Data Pool Details') }}
            </h2>

            <div class="space-x-4">
                <!-- Data Consumption Button -->
                <a href="{{ route('datapools.dataConsumption', ['id' => $datapool]) }}">
                    <button class="inline-flex btn indigo" style="justify-content: center; width: 250px; width: 230px; background-color: #3730a2; color: white;" wire:loading.attr="disabled" type="submit">
                        <span >View Data Consumption</span>
                    </button>
                </a>

               <!-- Add Data Top up Button -->
               <a href="{{ route('datapools.add-bolton', ['id' => $datapool]) }}">
                    <button class="inline-flex btn indigo" style="justify-content: center; width: 250px; background-color: #3730a2; color: white;" wire:loading.attr="disabled" type="submit">
                        <span >Add Data Top-Up </span>
                    </button>
                </a>


               <!-- Manage Service Data Limits Button -->
               <a href="{{ route('datapools.manage', ['id' => $datapool]) }}">
                    <button class="inline-flex btn indigo" style="justify-content: center; width: 250px; background-color: #3730a2; color: white;" wire:loading.attr="disabled" type="submit">
                        <span>Manage Service Data Limits </span>
                    </button>
                </a>

                <!-- Delete Data Pool Button -->
                <livewire:datapools.data-pool-delete :datapool_id="$datapool" />



                {{-- <x-jet-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <x-ui.forms.btn-load label="Actions" id="Action-button"
                                            icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                                    </svg>'>
                        </x-ui.forms.btn-load>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Data Pool Management -->
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Manage Data Pool') }}
                        </div>

                        <x-jet-dropdown-link href="{{ route('datapools.manage', ['id' => $datapool]) }}">
                            {{ __('Manage Pool Services') }}
                        </x-jet-dropdown-link>

                        <livewire:datapools.data-pool-delete :datapool_id="$datapool" />

                        <x-jet-dropdown-link href="{{ route('datapools.add-bolton', ['id' => $datapool]) }}">
                            {{ __('Add Once Off Bolton') }}
                        </x-jet-dropdown-link> --}}

                        {{-- <x-jet-dropdown-link href="{{ route('datapools.addDataTopup', ['id' => $datapool]) }}">
                            {{ __('Add Data Top-Up') }}
                        </x-jet-dropdown-link> --}}

                        {{-- <x-jet-dropdown-link href="{{ route('datapools.dataLimitConfig', ['id' => $datapool]) }}">
                            {{ __('Data Limit Configuration') }}
                        </x-jet-dropdown-link> --}}

                        {{-- <x-jet-dropdown-link href="{{ route('datapools.dataConsumption', ['id' => $datapool]) }}">
                            {{ __('View Data Consumption') }}
                        </x-jet-dropdown-link>

                    </x-slot>
                </x-jet-dropdown> --}}
            </div>
        </div>
    </x-slot>


    <div class="mx-auto mt-4">
        <div class="bg-white shadow-xl rounded-xl">
            @if($datapool)
                <livewire:datapools.data-pool-show :id=$datapool />
            @endif
        </div>
    </div>

    <div class="mx-auto mt-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold leading-tight text-gray-800">
                {{ __('Data Pool Services') }}
            </h2>
            <div class="space-x-4">
                <!-- Add Services from File Button -->
                <a href="{{ route('datapools.manage.add.fromFile', ['id' => $datapool]) }}">
                    <button class="inline-flex btn indigo" style="justify-content: center; width: 250px; background-color: #3730a2; color: white;" wire:loading.attr="disabled" type="submit">
                        <span>Add Services From File</span>
                    </button>
                </a>

               <!-- Transfer Services From File Button -->
                <a href="#">
                    <button class="inline-flex btn indigo" style="justify-content: center; width: 250px; background-color: #3730a2; color: white;" wire:loading.attr="disabled" type="submit">
                        <span>Transfer Services From File </span>
                    </button>
                </a>

                <!-- Add Services Button -->
                <a href="{{ route('datapools.manage.add', ['id' => $datapool]) }}">
                    <button class="inline-flex btn indigo" style="justify-content: center; width: 250px; background-color: #3730a2; color: white;" wire:loading.attr="disabled" type="submit">
                        <span>Add Services</span>
                    </button>
                </a>

            </div>

        </div>
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg mt-4">
            @if($datapool)
                <livewire:datapools.mobile-services.list-data-pool-services :datapool_id="$datapool" />
            @endif
        </div>
    </div>


</x-app-layout>


