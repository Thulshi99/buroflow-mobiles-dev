@php
$id = $item['infrastructureId'];
$status = $item['serviceStatus'] ?? 'N/A';
$class = $item['serviceabilityClass'] ?? 'N/A';
$pairStatus = $item['copperPairStatus'] ?? 'N/A';
$upSpeed = isset($item['upstream']) ? "{$item['upstream']['lowerRate']} - {$item['upstream']['upperRate']}" : 'N/A';
$downSpeed = isset($item['downstream']) ? "{$item['downstream']['lowerRate']} - {$item['downstream']['upperRate']}" : 'N/A';
@endphp
<div class="mt-2 panel bg-indigo-500/10">
    <h3 class="text-lg font-semibold text-indigo-700">{{ $item['infrastructureId'] }}</h3>
    <div class="grid grid-cols-2">
        <div>
            <x-sq.infrastructure.details :infrastructure="$item" />
            <strong>Pair Status: </strong>{{ $pairStatus }}<br>
            <strong>Upstream: </strong>{{ $upSpeed }}<br>
            <strong>Downstream: </strong>{{ $downSpeed }}<br>
        </div>
        <div>
            <x-sq.infrastructure.ref-data :item="$item" />
        </div>
    </div>
</div>
