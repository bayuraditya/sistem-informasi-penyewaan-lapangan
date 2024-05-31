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
        <p>Catatan : {{$order->note}}</p> <br>
        <p>total bayar : Rp{{$totalPrice}}</p> <br>

        <p class="d-none" id="created_at_{{$transaction->id}}">{{$transaction->created_at}}</p>
        <h3 id="countdown_{{$transaction->id}}"></h3>
        <script>
                                
                                var createdAt{{$transaction->id}} = document.getElementById("created_at_{{$transaction->id}}").innerHTML;
                                console.log(createdAt{{$transaction->id}});
                                var endTimeString{{$transaction->id}} = createdAt{{$transaction->id}};
                                var endTimeDate{{$transaction->id}} = new Date(endTimeString{{$transaction->id}});
                                var gmt8 = { timeZone: 'Asia/Singapore', hour12: false };
                                var endTimeStringFormatted{{$transaction->id}} = endTimeDate{{$transaction->id}}.toLocaleString('en-US', gmt8);
                                var endTime{{$transaction->id}} = new Date(endTimeStringFormatted{{$transaction->id}});
                                endTime{{$transaction->id}}.setTime(endTime{{$transaction->id}}.getTime() + 15 * 60 * 1000);

                                var countdownDate{{$transaction->id}} = endTime{{$transaction->id}};
                                var x = setInterval(function() {
                                    var now = new Date().getTime();
                                    var distance{{$transaction->id}} = countdownDate{{$transaction->id}} - now;
                                    var hours = Math.floor((distance{{$transaction->id}} % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                    var minutes = Math.floor((distance{{$transaction->id}} % (1000 * 60 * 60)) / (1000 * 60));
                                    var seconds = Math.floor((distance{{$transaction->id}} % (1000 * 60)) / 1000);
                                    

                                    document.getElementById('countdown_{{$transaction->id}}').innerHTML = 'Sisa Waktu ' + ("0" + hours).slice(-2) + ":" + ("0" + minutes).slice(-2) + ":" + ("0" + seconds).slice(-2);
                                    if (distance{{$transaction->id}} < 0) {
                                        document.getElementById('pay-button').classList.add('d-none');
                                        document.getElementById('pay-button').classList.add('disabled');
                                        document.getElementById('countdown_{{$transaction->id}}').innerHTML = 'Pembayaran Gagal';
                                        document.getElementById('countdown_{{$transaction->id}}').classList.add('text-danger');

                                        clearInterval(x);
                                    }
                                }, 1000);
        </script>


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