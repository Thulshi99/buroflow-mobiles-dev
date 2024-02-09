<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Cards') }}
        </h2>
    </x-slot>
    <!-- This example requires Tailwind CSS v2.0+ -->
<div class="flex flex-col">
    <div class="-my-2 overflow-x-auto">
        <div class="inline-block min-w-full py-2 align-middle">
            <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
			<tr>
                <th scope="col" class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Reference</th>
                <th scope="col" class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Card ID</th>
                <th scope="col" class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Reseller</th>
                <th scope="col" class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Card Stage</th>
                <th scope="col" class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Order Status</th>
                <th scope="col" class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Transfer Date</th>
                <th scope="col" class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">LOC ID</th>
                <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Address</th>
                <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">City</th>
                {{-- <th>Carrier</th> --}}
                {{-- <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Copper Pair ID</th> --}}
                {{-- <th>Account Number</th> --}}

                <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Show</th>
                {{-- <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Edit</th> --}}
			</tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                    @if (count($cards) > 0)
                        @foreach ($cards as $card)
                            <?php $orderStatus = $card['fields']['customfield_dropdown4'];
                                switch($orderStatus)
                                {
                                    case '31914000000046147':
                                        $card['fields']['customfield_dropdown4'] =  'Submitted';
                                        break;
                                    case '31914000000046148':
                                        $card['fields']['customfield_dropdown4'] =  'Awaiting Transfer';
                                        break;
                                    case '31914000000046149':
                                        $card['fields']['customfield_dropdown4'] =  'Transfer Completed';
                                        break;
                                    case '31914000000046150':
                                        $card['fields']['customfield_dropdown4'] =  'Cancelled';
                                        break;
                                    case '31914000000046151':
                                        $card['fields']['customfield_dropdown4'] =  'Error';
                                        break;
                                    case '31914000000046152':
                                        $card['fields']['customfield_dropdown4'] =  'Closed';
                                        break;
                                    default:
                                        $card['fields']['customfield_dropdown4'] =  '';
                                } ?>
                            <tr>
                                <td class="px-3 py-3 whitespace-wrap">{{ $card['fields']['customfield_shorttext22'] }}</td>
                                <td class="px-3 py-3 whitespace-wrap">{{ $card['id'] }}</td>
                                <td class="px-3 py-3 whitespace-wrap">{{ $card['fields']['customfield_shorttext24'] }}</td>
                                <td class="px-3 py-3 whitespace-wrap">{{ $card['stage_name'] }}</td>
                                <td class="px-3 py-3 whitespace-wrap">{{ $card['fields']['customfield_dropdown4'] }}</td>
                                <td class="px-3 py-3 whitespace-wrap">{{ $card['due_date_hours'] }}</td>
                                <td class="px-3 py-3 whitespace-nowrap">{{ $card['fields']['customfield_shorttext14'] }}</td>
                                <td class="px-4 py-3 whitespace-wrap">{{ $card['fields']['customfield_shorttext23'].' '. $card['fields']['customfield_shorttext9']}}</td>
                                <td class="px-4 py-4 whitespace-nowrap">{{ $card['fields']['customfield_shorttext8'] }}</td>
                                {{-- <td>{{ $card['fields']['customfield_shorttext25'] }}</td> --}}
                                {{-- <td class="px-4 py-3 whitespace-nowrap">{{ $card['fields']['customfield_shorttext21'] }}</td> --}}
                                {{-- <td>{{ $card['fields']['customfield_shorttext8'] }}</td> --}}

                                <td class="px-4 py-3 whitespace-nowrap"><a href="{{ route('qntrl.show', $card['id']) }}">Show</a></td>
                                {{-- <td class="px-4 py-4 whitespace-nowrap"><a href="{{ route('qntrl.edit', $card['id']) }}">Edit</a></td> --}}
                                    {{-- <form method="post" action="{{ route('qntrl.delete', $card['id']) }}">
                                    @method('DELETE')
                                    <button>Delete</button>
                                    </form> --}}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                
            </tbody>
        </table>
        </div>
		</div>
		</div>
    </div>
</x-app-layout>
