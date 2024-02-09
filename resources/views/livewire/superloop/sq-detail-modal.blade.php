@unless (empty($sq))
    Plans available 
    @foreach ($sq->details->availableProducts as $name => $product)
        {{implode(', ', $product->options)}}
    @endforeach
@empty
    No details to show!
@endunless
