@php
    $infrastructureDetails = [
        'locationId' => 'LocID',
        'networkCoexistence' => 'Network Coexistence',
        'serviceabilityClass' => 'Class',
        'ntdType' => 'NTD Type',
        'ntdPowerType' => 'NTD Power Type',
        'batteryPowerUnit' => 'Battery PowerUnit',
        'ntdLocation' => 'NTD Location',
        //'subsequentInstallationCharge' => 'Subsequent Installation Charge',
    ];
@endphp
@foreach ($infrastructureDetails as $detail => $label)
    @isset($infrastructure[$detail])
        <strong>{{$label}}: </strong>{{$infrastructure[$detail]}}<br>
    @endisset
@endforeach
