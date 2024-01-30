@extends('layouts.checkout-app')

@section('content')
<div class="m-5">
    <h1>Pembayaran</h1>

            tanggal : {{$reservation->date}} <br>
            lapangan : {{$reservation->court->court_name}} <br>
            sesi : {{ implode(", ", $reservation->rental_session_times)}} <br>
            
            @foreach($reservation->rental_session_times as $r)
            {{$r+=6}}.00 - {{$r+=1}}.00 Rp40.000 <br>
            @endforeach
            
            total sesi : {{sizeof($reservation->rental_session_times)}} <br>
            total Bayar : {{sizeof($reservation->rental_session_times)*40000}} <br>

                <!-- tanggal  :  -->
                <input type="date" name="date" id=""value="{{$reservation->date}}" class="d-none"  > <br>
                <!-- lapangan :  -->
                <input type="number" value="{{$reservation->court->id}}" name="court" class="d-none" id="" > <br>
                @foreach($reservation->rental_session_times as $r)
                    <!-- sesi : -->
                    <input type="checkbox" name="rental_session_times[]" id="" value="{{$r}}" class="d-none"  checked> <br>
                @endforeach
                <input type="number" class="d-none" value="{{$transaction->id}}" name="transaction_id">
                  
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