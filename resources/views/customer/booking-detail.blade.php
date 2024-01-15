@extends('layouts.app')

@section('content')

<div class="m-5">
    <h1>Detail Pesanan</h1>
    tanggal : {{$reservation->date}} <br>
    lapangan : {{$reservation->court->court_name}} <br>
    sesi : {{ implode(", ", $reservation->rental_session_times)}} <br>
    
    @foreach($reservation->rental_session_times as $r)
    {{$r+=6}}.00 - {{$r+=1}}.00 Rp40.000 <br>
    @endforeach
    
    total sesi : {{sizeof($reservation->rental_session_times)}} <br>
    total Bayar : {{sizeof($reservation->rental_session_times)*40000}} <br>
    <button type="submit" class="btn btn-primary" >Bayar Sekarang</button>

</div>

@endsection