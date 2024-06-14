
@extends('layouts.app')

@section('content')
<br>
    <div class="container">
        <h1>Riwayat Reservasi</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">Tanggal</th>
                <th scope="col">Jam</th>
                <th scope="col">Lapangan</th>
                <th scope="col">Status</th>
                <th scope="col">Detail</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservations as $r)
                <tr>
                    <td>{{$r->date}}</td>
                    <td>{{$r->rental_session_time}}</td>
                    <td>{{$r->court_name}}</td>
                    <td>{{$r->transactions[0]->payment_status}}</td>
                    <td> 
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reservationModal_{{$r->id}}">
                            Detail Reservasi
                        </button>
                    </td>
                    @endforeach
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        @foreach($reservations as $r)
            @foreach($r->transactions as $t)
            <div class="modal fade" id="reservationModal_{{$r->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Reservasi</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                        <table>
                                            <h2>{{$t->payment_status}}
                                            </h2>
                                            <tr>
                                                <td>Id Transaksi</td>
                                                <td> : {{$t->id}}</td>
                                            </tr>
                                            <tr>
                                                <td>Total Bayar</td>
                                                <td> : {{$t->total_amount}}</td>
                                            </tr>
                                            <tr>
                                                <td>Metode Pembayaran</td>
                                                <td> : {{$t->payment_method}}</td>
                                            </tr>
                                            <tr>
                                                <td>Tanggal Bayar</td>
                                                <td> : {{$t->settlement_time}}</td>
                                            </tr>
                                            @if($t->payment_status == 'pending')
                                                <tr>
                                                    <td>
                                                        <button type="submit" class="btn btn-primary" id="pay-button{{$t->id}}">Selesaikan Pembayaran</button>
                                                       <!-- payment gateway snap -->
                                                        <script type="text/javascript">
                                                        // For example trigger on button clicked, or any time you need
                                                        var payButton = document.getElementById('pay-button{{$t->id}}');
                                                        payButton.addEventListener('click', function () {
                                                            // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
                                                            window.snap.pay('{{$t->snapToken}}', {
                                                            onSuccess: function(result){
                                                                /* You may add your own implementation here */
                                                                //alert("payment success!");
                                                                window.location.href = ''
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
                                                    </td>
                                                </tr>
                                            @endif
                                        </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                                </div>
                            </div>
                        </div>
            @endforeach
        @endforeach

        @foreach($reservations as $r)
            @foreach($r->transactions as $t)
       
         
                                                             @endforeach
        @endforeach
@endsection


