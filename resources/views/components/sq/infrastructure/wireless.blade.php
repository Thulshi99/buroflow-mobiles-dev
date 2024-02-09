<div class="mt-2 panel bg-sky-500/10">
    <h3 class="text-lg font-semibold text-sky-700">{{ $item['infrastructureId'] }}</h3>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <x-sq.infrastructure.details :infrastructure="$item" />
        </div>
        <div>
            <x-sq.infrastructure.ref-data :item="$item" />
        </div>
    </div>
    <div class="grid grid-cols-4 gap-3">
        @foreach ($item['ntdPorts'] as $port)
            <div class="p-4 mt-3 mb-1 border border-gray-400 shadow rounded-xl">
                <strong>PortId</strong> {{ $port['portId'] }}<br>
                <strong>PortName</strong> {{ $port['portName'] }}<br>
                <strong>UniType</strong> {{ $port['uniType'] }}<br>
                <strong>Status</strong> {{ $port['status'] }}<br>
                <x-sq.infrastructure.ref-data :item="$port" />
            </div>
        @endforeach
    </div>
</div>
