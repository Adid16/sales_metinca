@if($purchase_order)
    @php
        $status = $purchase_order->status;
        $step = 1; 
        
        if ($status == 'review') {
            $step = 2; 
        } elseif ($status == 'production') {
            $step = 3; 
        } elseif ($status == 'ship') {
            $step = 4;
        }
    @endphp
@endif