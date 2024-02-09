<x-app-layout>
    <style>
    .custom-button {
        /* Add your additional styles here */
        font-size: 16px;
        font-weight: bold;
        /* Add any other styles you want */
    }

    </style>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Cards') }}
        </h2>
        <a href="{{ route('simcard-qntrl.create') }}" class="ml-auto inline-block px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-800 custom-button">
            Create
        </a>
        
    </x-slot>

    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto">
            <div class="inline-block min-w-full py-2 align-middle">
                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">layout id</th>
                                <th scope="col" class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">requestor name</th>
                                <th scope="col" class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">card id</th>
                                <th scope="col" class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Show</th>
                                <th scope="col" class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Edit</th>
                                
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if ($cards && count($cards) > 0)
                             @foreach ($cards as $card)
                             <tr>
                             
                                 <td class="px-3 py-3 whitespace-wrap">{{ $card['layout_id'] }}</td>
                                 <td class="px-3 py-3 whitespace-wrap">{{ $card['requestor_name'] }}</td>
                                 <td class="px-3 py-3 whitespace-wrap">{{ $card['id'] }}</td>
                                 <td class="px-4 py-3 whitespace-nowrap"><a href="{{ route('simcard-qntrl.show', $card['id']) }}">Show</a></td>
                                 <td class="px-4 py-4 whitespace-nowrap"><a href="{{ route('simcard-qntrl.edit', $card['id'])}}">Edit</a></td>
                                     {{-- <form method="post" action="{{ route('simcard-qntrl.delete', $card['id']) }}">
                                     @method('DELETE')
                                     <button>Delete</button>
                                     </form> --}}
                                 </td>
                             </tr>
                         @endforeach
                               
                            @else
                                <tr>
                                    <td colspan="5">No cards found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
