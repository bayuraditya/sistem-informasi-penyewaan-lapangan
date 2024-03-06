
@extends('layouts.app')

@section('content')
<div class="m-5">

    <h1>Data Reservasi</h1>
    <!-- 
    tanggal (default nya today)
    lapangan/court
    tabel : tanggal, court, nama, sesi, payment status, 

     -->
     <p>User : {{$user->name}}</p>
     <a href="/admin" class="btn btn-primary">HOME</a> <br><br>
    <form id="logout-form" action="{{ route('logout') }}" method="POST">
    @csrf
        <button type="submit" class="btn btn-danger">{{ __('Logout') }}</button>
    </form> 
  <!-- tanggal dan court -->
     <form action="reservations" method="get">
        <label for="date">Pilih Tanggal</label>
        <input type="date" name="date" id=""> <br>
        <label for="court">Pilih Lapangan</label>
        <select name="court_id" id="" >
            @foreach($allCourt as $c)
            <option value="{{$c->id}}">{{$c->court_name}}</option>
            @endforeach
        </select><br>
        <input type="submit" value="Submit" class="btn btn-primary">
     </form>

<br>
<table>
    <tbody>
        <tr>
            <td>Tanggal</td>
            <td> : </td>
            <td>{{$date}}</td>
        </tr>
        <tr>
            <td>Lapangan</td>
            <td> : </td>
            <td>{{$court->court_name}}</td>
        </tr>
    </tbody>
</table>
<br>
<!-- default: tanggal hari ini, lapangan A -->
     <table class="table table-bordered">
        <thead>
            <tr>
                <td>Sesi</td>
                <td>Jam</td>
                <td>Nama</td>
                <td>No HP</td>
                <td>Lapangan</td>
                <td>Tanggal</td>
                <td>Status Pembayaran</td>
                <td>Metode Pembayaran</td>
            </tr>
        </thead>
        <tbody>
            @foreach($rentalSession as $ren)
           <tr>
                <td>{{$ren->id}}</td>
                <td>{{$ren->rental_session_time}}</td>
                 <td>
                    @foreach($reservations as $res)
                    @if($res->rental_session_time == $ren->rental_session_time)
                   {{$res->name}}
                    @else
                 
                    @endif
                    @endforeach
                    </td>
                <td>
                @foreach($reservations as $res)
                    @if($res->rental_session_time == $ren->rental_session_time)
                   {{$res->handphone_number}}
                    @else
                 
                    @endif
                    @endforeach
                </td>
                <td>
                @foreach($reservations as $res)
                    @if($res->rental_session_time == $ren->rental_session_time)
                   {{$res->court_name}}
                    @else
                 
                    @endif
                    @endforeach
                </td>
                <td>
                @foreach($reservations as $res)
                    @if($res->rental_session_time == $ren->rental_session_time)
                   {{$res->date}}
                    @else
                 
                    @endif
                    @endforeach
                </td>
                <td>
                @foreach($reservations as $res)
                    @if($res->rental_session_time == $ren->rental_session_time)
                        @if($res->payment_status == 'settlement' || 'capture')
                        <p>Lunas</p>
                        @endif
                    @else
                 
                    @endif
                    @endforeach
                </td>
                <td>
                @foreach($reservations as $res)
                    @if($res->rental_session_time == $ren->rental_session_time)
                        {{$res->payment_method}}
                    @else
                 
                    @endif
                    @endforeach
                </td>
                
           </tr>
           @endforeach

        </tbody>
    </table>
</div>
    
@endsection