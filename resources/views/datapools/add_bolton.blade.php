<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add Data Top-up ') }}
            </h2>
            {{-- @if(\App\Models\User::find(auth()->user()->id)->tenant_role == 'admin')
            <div class="flex justify-end">
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
                            <!-- Actions -->
                            <x-jet-dropdown-link href="{{ route('datapools.show', ['datapool' => $datapool]) }}">
                                {{ __('Manage Data Pool') }}
                            </x-jet-dropdown-link>

                            <x-jet-dropdown-link href="{{ route('datapools.manage', ['id' => $datapool]) }}">
                                {{ __('Manage Pool Services') }}
                            </x-jet-dropdown-link>

                        </x-slot>
                    </x-jet-dropdown>
                </div>
            </div>
            @endif--}}
        </div>
    </x-slot>

    <div class="mx-auto">
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
            {{-- <livewire:datapools.data-consumption-summary /> --}}
            <div class="ml-auto" style="margin-right: 50px; padding-left:15px; padding-top:20px; padding-bottom:20px;">
                <table>
                    <tr>
                        @php
                            $datapool = App\Models\DataPool::find($datapool_id);
                        @endphp
                        <td><strong>Data Pool ID:</strong></td>
                        <td class="pl-4">{{ $datapool->datapool_id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Data Pool Name:</strong></td>
                        <td class="pl-4">{{ $datapool->description }}</td>
                    </tr>
                </table>
            </div>

            <livewire:datapools.add-bolton :datapool_id="$datapool_id" />
        </div>
        {{-- <div class="bg-white shadow-xl rounded-xl">
            <livewire:datapools.add-bolton :datapool_id="$datapool" />
        </div> --}}
    </div>
</x-app-layout>

