@php
$infrastructureId = $this->data['infrastructureId'];
$infrastructure = data_get($this->sq->raw, "infrastructures.{$infrastructureId}");
$ports = data_get($infrastructure, 'ntdPorts.*') ?? [];
$idLabel = data_get($infrastructure, 'infrastructureId');
$locStatus = "(" . data_get($this->sq->raw, "status") . ")" ?? '';
$status = data_get($infrastructure, 'serviceStatus') ?? '';
$upstream = data_get($infrastructure, 'upstream');
$downstream = data_get($infrastructure, 'downstream');
$providerName = data_get($infrastructure, 'referencedData.serviceProviderName');

@endphp
{{-- [
        [
            'portId' => '1',
            'portName' => '1-UNI-D1',
            'uniType' => 'UNI-D',
            'status' => 'Used',
            'referencedData' => [
                'productInstanceId' => 'PRI000254530526',
                'serviceProviderId' => '0003',
                'serviceProviderName' => 'AAPT Limited',
                'owner' => false,
            ],
            'fees' => [
                'tc4' => [
                    [
                        'name' => 'SERVICE_TRANSFER_FEE',
                        'oneTimeCharge' => [
                            'amount' => '10.00',
                            'currency' => 'AUD',
                            'symbol' => '&#36;',
                        ],
                        'monthlyRecurringCharge' => [
                            'amount' => '0.00',
                            'currency' => 'AUD',
                            'symbol' => '&#36;',
                        ],
                    ],
                ],
            ],
        ],
        [
            'portId' => '2',
            'portName' => '1-UNI-D2',
            'uniType' => 'UNI-D',
            'status' => 'Free',
            'referencedData' => null,
            'fees' => [
                'tc4' => [],
            ],
        ],
        [
            'portId' => '3',
            'portName' => '1-UNI-D3',
            'uniType' => 'UNI-D',
            'status' => 'Free',
            'referencedData' => null,
            'fees' => [
                'tc4' => [],
            ],
        ],
        [
            'portId' => '4',
            'portName' => '1-UNI-D4',
            'uniType' => 'UNI-D',
            'status' => 'Free',
            'referencedData' => null,
            'fees' => [
                'tc4' => [],
            ],
        ],
    ], --}}



<x-forms::field-wrapper :id="$getId()" :label-sr-only="$isLabelHidden()" :helper-text="$getHelperText()" :hint="$getHint()" :hint-icon="$getHintIcon()"
                        :required="$isRequired()" :state-path="$getStatePath()">
    <fieldset>
        @unless(empty($ports))
        <legend class="text-sm font-medium leading-4 text-gray-700">Select a Port</legend>
        @endunless
        <div class="grid grid-cols-1 mt-4 gap-y-6 sm:grid-cols-4 sm:gap-x-4" x-data="{ portId: '' }"
             x-init="$watch('portId', value => console.log(value))">
            @forelse ($ports as $index => $port)
            @if ($port['status'] != 'Free')
                <label @if (data_get($port, 'referencedData.serviceProviderId') == '0003') @disabled(true) @endif
                       class="relative flex p-4 bg-white border border-gray-300 rounded-lg shadow-sm cursor-pointer focus:outline-none"
                       :class="{
                           'border-indigo-500 ring-2 ring-indigo-500': portId ==
                               {{ $port['portId'] }},
                           'border-gray-300': portId == {{ $port['portId'] }}
                       }">
                    <input name="data.portId" wire:model.defer="data.portId"
                           id="{{ $getId() }}-{{ $port['portId'] }}" type="radio" value="{{ $port['portId'] }}"
                           class="sr-only" aria-labelledby="portId-{{ $port['portId'] }}-label"
                           aria-describedby="portId-{{ $port['portId'] }}-type portId-{{ $port['portId'] }}-service-provider portId-{{ $port['portId'] }}-status" @if (data_get($port, 'referencedData.serviceProviderId') == '0003') @disabled(true) @endif
                           x-model="portId">
                    <span class="flex flex-1">
                        <span class="flex flex-col">
                            <span id="portId-{{ $port['portId'] }}-label"
                                  class="block text-sm font-medium text-gray-900">
                                {{ $port['portName'] }}
                                <span id="portId-{{ $port['portId'] }}-type" class="text-sm text-gray-500">
                                    ({{ $port['uniType'] }})
                                </span>
                            </span>

                            <span id="portId-{{ $port['portId'] }}-service-provider" class="text-gray-500">
                                {{ data_get($port, 'referencedData.serviceProviderName') }}
                            </span>
                            <span id="portId-{{ $port['portId'] }}-status"
                                  class="mt-auto text-sm font-semibold {{ $port['status'] == 'Free' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $port['status'] }} </span>
                                @if (data_get($port, 'referencedData.serviceProviderId') == '0003') <span id="portId-{{ $port['portId'] }}-label"
                                class="block text-sm font-medium text-red-900">Warning: AAPT cannot be selected. Select another or raise a support ticket. </span> @endif
                        </span>
                    </span>
                    <!-- Heroicon name: solid/check-circle -->
                    <svg class="invisible w-5 h-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 20 20" :class="{ 'invisible': portId != {{ $port['portId'] }} }"
                         fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                              clip-rule="evenodd" />
                    </svg>
                    <!--
                Active: "border", Not Active: "border-2"
                Checked: "border-indigo-500", Not Checked: "border-transparent"
            -->
                    <span class="absolute border-2 rounded-lg pointer-events-none -inset-px" aria-hidden="true"
                          :class="{
                              'border border-indigo-500 ring-2 ring-indigo-500': portId ==
                                  {{ $port['portId'] }},
                              'border-transparent border-2': portId != {{ $port['portId'] }}
                          }"></span>
                </label>
                @endif
            @empty
                <div class="relative p-4 bg-white border border-gray-300 rounded-lg shadow-sm cursor-pointer focus:outline-none">
                @if (Str::startsWith($idLabel, 'CPI'))
                    <strong>{{ $idLabel }}</strong> {{$locStatus}}<br>
                    <strong>Status:</strong> {{ $status }} <span class="text-gray-400">{{ "($providerName)" ?? '' }}</span><br>
                    <strong>Upstream:</strong> {{ $upstream['lowerRate'] }} - {{ $upstream['upperRate'] }}<br>
                    <strong>Downstream:</strong> {{ $downstream['lowerRate'] }} - {{ $downstream['upperRate'] }}<br>
                    <strong>Coexistence:</strong> {{ data_get($infrastructure, 'networkCoexistence') }}<br>
                    <br>
                @endif
                No ports on infrastructure
                </div>
            @endforelse
        </div>
    </fieldset>
</x-forms::field-wrapper>
