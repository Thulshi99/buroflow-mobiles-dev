<div class="mt-2 panel bg-green-500/10">
    <h3 class="text-lg font-semibold text-green-700">{{ $item['infrastructureId'] }}</h3>
    <x-sq.infrastructure.details :infrastructure="$item" />
    <div class="grid grid-cols-4 gap-3">
        @foreach ($item['ntdPorts'] as $port)
            <div class="p-4 mt-3 mb-1 border border-gray-400 shadow rounded-xl">
                <strong>PortId</strong> {{ $port['portId'] }}<br>
                <strong>PortName</strong> {{ $port['portName'] }}<br>
                <strong>UniType</strong> {{ $port['uniType'] }}<br>
                <strong>Status</strong> {{ $port['status'] }}<br>
                @isset($port['referencedData'])
                    <x-sq.infrastructure.ref-data :item="$port" />
                @endisset
            </div>
        @endforeach
    </div>
</div>
