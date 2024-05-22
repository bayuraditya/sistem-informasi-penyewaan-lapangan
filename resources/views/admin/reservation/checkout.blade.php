@extends('layouts.checkout-app')

@section('content')
<div class="m-5">
    <h1>Pembayaran</h1>
        tanggal : {{$order['date']}} <br>

        @php
        $i=1;
        $session = 0;
        $totalPrice = 0;
        @endphp
        
        @foreach($reservationDetail as $index => $reservation)   
            @foreach($reservation as $courtId => $courts)
                @foreach($courts['sesi'] as $rentalSessionId => $rentalSessionDetail)
                    <table>
                            <tr>
                                <td>
                                {{$i++}}. 

                                </td>
                                <td>
                                    lapangan 
                                </td>
                                <td>
                                    : {{$courts['court_name']}}
                                </td>
                            </tr>
                            <tr>
                                <td>

                                </td>
                                <td>
                                sesi 
                                </td>
                                <td>
                                : {{$rentalSessionId}}    
                                </td>
                            </tr>
                            <tr>
                                <td>

                                </td>
                                <td>
                                jam  
                                </td>
                                <td>
                                : {{$rentalSessionDetail['time']}} 
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                harga 
                                </td>
                                <td>
                                : {{$rentalSessionDetail['price']}} 
                                </td>
                            </tr>
                        </table>
                        @php
                        $totalPrice+=$rentalSessionDetail['price']
                        @endphp
                        <input type="checkbox" class="form-check-input d-none" name="reservation[{{$courtId}}][]" id="" value="{{$rentalSessionId}}" checked>
                @endforeach
            @endforeach
        @endforeach
        <br>
        <p>Catatan : Rp{{$totalPrice}}</p> <br>
        <p>total bayar : Rp{{$totalPrice}}</p> <br>
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