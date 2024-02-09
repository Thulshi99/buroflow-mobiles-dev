@props(["item" => null])

<div class="pb-3 text-gray-700">
    <strong class="underline">Reference Data</strong><br>
    @isset($item['referencedData'])
    <strong>ProductInstanceId</strong> {{$item['referencedData']['productInstanceId']}}<br>
    <strong>ServiceProvider</strong> {{$item['referencedData']['serviceProviderName']}} {{"({$item['referencedData']['serviceProviderId']})"}}<br>
    {{-- <strong>Owner</strong> {{$item['referencedData']['owner'] ? 'True' : 'False'}}<br> --}}
    @else
    No data available
    @endisset
</div>
