@extends('layouts.checkout-app')

@section('content')
<div class="m-5">
    <h1>Pembayaran</h1>
    tanggal : {{$order['date']}} <br>

    @php
    $i=1;
    $sesi = 0;
    @endphp
    @foreach($order['reservation'] as $reservationIndex => $reservation)
        @foreach($reservation as $reservationIndex2 => $reservation2)
            {{$i++}} <br>
            lapangan : {{$reservationIndex}} <br> 
            sesi : {{$reservation2}} <br>
            jam : {{$rentalSession[$reservation2]->rental_session_time}}

            @php
            $sesi++
            @endphp
            
            <input type="checkbox" class="form-check-input d-none" name="reservation[{{$reservationIndex}}][]" id="" value="{{$reservation2}}" checked>
    <br>
        @endforeach
    @endforeach

    <br>

    total sesi :  {{$sesi}} <br>
    total bayar : Rp{{$sesi*40000}}  <br>
                <button type="submit" class="btn btn-primary" id="pay-button">Selesaikan Pembayaran</button>
        
  
<!-- payment gateway snap -->


        <script type="text/javascript">
            // For example trigger on button clicked, or any time you need
            var payButton = document.getElementById('pay-button');
            payButton.addEventListener('click', function () {
                // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
                window.snap.pay('{{$snapToken}}', {
                onSuccess: function(result){
                    /* You may add your own implementation here */
                    //alert("payment success!");
                    window.location.href = '/invoice/{{$transaction->id}}'
                    console.log(result);
                },
                onPending: function(result){
                    /* You may add your own implementation here */
                    alert("wating your payment!"); console.log(result);
                },
                onError: function(result){
                    /* You may add your own implementation here */
                    alert("payment failed!"); console.log(result);
                },
                onClose: function(){
                    /* You may add your own implementation here */
                    alert('you closed the popup without finishing the payment');
                }
                })
            });
            </script>

</div>
@endsection