
@extends('layouts.app')

@section('content')
<div class="m-5">
    <h1>Data Reservasi</h1>
    <!-- 
    tanggal (default nya today)
    lapangan/court
    tabel : tanggal, court, nama, sesi, payment status, 

     -->

  <!-- tanggal dan court -->
     <form action="/reservation" method="get">
        <label for="date">Pilih Tanggal</label>
        <input type="date" name="date" id=""> <br>
        <label for="court">Pilih Lapangan</label>
        <select name="court" id="" >
            @foreach($allCourt as $c)
            <option value="{{$c->id}}">{{$c->court_name}}</option>
            @endforeach
        </select><br>
        <input type="submit" value="Submit" class="btn btn-primary">
     </form>

<br>
<!-- default: tanggal hari ini, lapangan A -->
     <table class="table table-bordered">
        <thead>
            <tr>
                <td>sesi</td>
                <td>jam</td>
                <td>nama</td>
                <td>lapangan</td>
                <td>tanggal</td>
                <td>status pembayaran</td>
            </tr>
        </thead>
        <tbody>
            @foreach($rentalSession as $r)
                @foreach($reservations as $res)
                    @if($r->id === $res->rental_session_id)
                        <tr>
                            <td>{{$r->id}}</td>
                            <td>{{$r->rental_session_time}}</td>
                            <td>{{$res->name}}</td>
                            <td>{{$res->court_name}}</td>
                            <td>{{$res->date}}</td>
                            <td>{{$res->payment_status}}</td>
                        </tr>
                    @else
                        <tr>
                            <td>{{$r->id}}</td>
                            <td>{{$r->rental_session_time}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
    
@endsection