<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add Services To Pool ') }}
            </h2>
            {{-- <div class="flex justify-end">
                <!-- Actions Dropdown -->
                <div class="relative ml-1">
                    <x-jet-dropdown align="right" width="48">
                        <x-slot name="trigger">

                            <x-ui.forms.btn-load label="Actions" id="Action-button"
                                                icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                                        </svg>'>
                            </x-ui.forms.btn-load>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Data Pool Services Management -->

                            <x-jet-dropdown-link href="{{ route('datapools.manage.add.fromFile', ['id' => $datapool]) }}">
                                {{ __('Add Services From File') }}
                            </x-jet-dropdown-link>


                            <x-jet-dropdown-link href="{{ route('datapools.manage', ['id' => $datapool]) }}">
                                {{ __('Manage Pool Services') }}
                            </x-jet-dropdown-link>

                            <x-jet-dropdown-link href="{{ route('datapools.show', ['datapool' => $datapool]) }}">
                                {{ __('Manage Data Pool') }}
                            </x-jet-dropdown-link>

                        </x-slot>
                    </x-jet-dropdown>
                </div>
            </div> --}}
        </div>
    </x-slot>

    <div class="mx-auto">
        <div class="bg-white shadow-xl rounded-xl">
            <livewire:datapools.mobile-services.add-new-services-list :datapool_id="$datapool" />
        </div>
    </div>
</x-app-layout>
