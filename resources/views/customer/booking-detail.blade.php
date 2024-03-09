@extends('layouts.app')

@section('content')

<div class="m-5">
    <h1>Detail Pesanan</h1>
  
  
    <form action="/checkout" method="post">
        @csrf
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

   <input type="date" name="date" id=""value="{{$order['date']}}" class="d-none"  > <br>
   <button type="submit" class="btn btn-primary" >Lanjutkan Pembayaran</button>
   </form>

</div>
@endsection