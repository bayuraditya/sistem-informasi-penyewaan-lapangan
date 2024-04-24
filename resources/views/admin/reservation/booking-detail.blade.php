@extends('layouts.app')

@section('content')

<div class="m-5">
    <h1>Detail Pesanan</h1>
  
  
    <form action="/checkout" method="post">
        @csrf
        tanggal : {{$order['date']}} <br>

        @php
        $i=1;
        $session = 0;
        $totalPrice = 0;
        @endphp

        @foreach($reservation as $index => $reservation)   
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

        total bayar : Rp{{$totalPrice}}  <br>

        <input type="date" name="date" id=""value="{{$order['date']}}" class="d-none"  > <br>
        <button type="submit" class="btn btn-primary" >Lanjutkan Pembayaran</button>
   </form>

</div>
@endsection