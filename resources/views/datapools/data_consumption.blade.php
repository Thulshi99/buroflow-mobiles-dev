<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Data Consumption') }}
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
            @endif --}}
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
                    <tr>
                        <td><strong>Pool Data Usage:</strong></td>
                        <td class="pl-4">
                            <div style="width: 300px; height: 20px; background-color: #f0f0f0; border-radius: 4px; overflow: hidden;">
                                @php
                                    // Initialize variables
                                    $total_allowance = isset($total_allowance) ? $total_allowance : 0;
                                    $data_usage = isset($data_usage) ? $data_usage : 0;

                                    // Check if $total_allowance is non-zero before calculating $usagePercentage
                                    if ($total_allowance > 0) {
                                        $usagePercentage = number_format(($data_usage / $total_allowance) * 100, 2);
                                    } else {
                                        $usagePercentage = 0.00;
                                    }

                                    // Determine the color based on $usagePercentage
                                    if ($usagePercentage > 130) {
                                        $color = '#7B1818';
                                    } elseif ($usagePercentage > 100) {
                                        $color = 'red';
                                    } elseif ($usagePercentage > 85) {
                                        $color = 'orange';
                                    } else {
                                        $color = 'green';
                                    }
                                @endphp

                                <div style="width: 300px; height: 20px; background-color: #f0f0f0; border-radius: 4px; overflow: hidden;">
                                    <div style="width: {{ $usagePercentage }}%; height: 100%; background-color: {{ $color }}; text-align: center; line-height: 20px; color: #fff;">
                                        {{ $usagePercentage }}%
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    {{-- <tr>
                        <td><strong>Billing Cycle:</strong></td>
                        <td class="pl-4">01-05</td>
                    </tr> --}}

                    <tr>
                        <td><strong>Total Data Allowance:</strong></td>
                        <td class="pl-4">{{ $total_allowance }} GB</td>
                    </tr>
                    <tr>
                        <td><strong>Total Monthly Usage:</strong></td>
                        <td class="pl-4">{{ $data_usage }} GB</td>
                    </tr>
                    <tr>
                        <td><strong>Remaining Data:</strong></td>
                        <td class="pl-4">{{ $remaining_data }}</td>
                    </tr>
                    <tr>
                        <td><strong>Start Date of Current Billing Cycle:</strong></td>
                        <td class="pl-4">{{ $start_date_billing_cycle }}</td>
                    </tr>
                    <tr>
                        <td><strong>Days to Go Before Start Of Next Cycle:</strong></td>
                        <td class="pl-4">{{ $days_remaining }}</td>
                    </tr>
                </table>
            </div>

            <livewire:datapools.data-consumption :datapool_id="$datapool_id" />
        </div>
    </div>

</x-app-layout>
