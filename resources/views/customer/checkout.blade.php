@extends('layouts.app')

@section('content')

<div class="m-5">
    <h1>Pembayaran</h1>
   
    <form action="/pay" method="post">
        @csrf
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
        <input type="number" class="d-none" value="{{$transaction->id}}">
        <button type="submit" class="btn btn-primary" id="pay-button">Selesaikan Pembayaran</button>
    
<!-- payment gateway snap -->
</div>


@endsection