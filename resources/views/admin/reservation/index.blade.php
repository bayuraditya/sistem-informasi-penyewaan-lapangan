
@extends('layouts.app')

@section('content')
<div class="m-5">

    <h1>Data Reservasi</h1>
     <p>User : {{$user->name}}</p>
    <form id="logout-form" action="{{ route('logout') }}" method="POST">
    @csrf
        <button type="submit" class="btn btn-danger">{{ __('Logout') }}</button>
    </form> <br>
    <a href="/admin/reservation/select-date" class="btn btn-primary">Tambah Reservasi</a> <br> <br>
  <!-- tanggal dan court -->
     <form action="reservation" method="get">
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
      
    </tbody>
</table>
<br>

<form action="/admin/reservation/export?date={{$date}}&court_id={{$court->id}}" method="post" >
        @csrf
        <input type="date" class="d-none" name="date" value="{{$date}}" id="">
        <input type="text" class="d-none" name="court_id" value="{{$court->id}}" id="">
            <button type="submit" class="btn btn-primary">Cetak</button>
        </form>
<br><br>
@foreach($allCourt as $c)
    <h2>Lapangan : {{$c->court_name}}</h2>




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
                <td>Action</td>
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
                                @if($res->court_id == $c->id)
                                    {{$res->name}}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($reservations as $res)
                            @if($res->rental_session_time == $ren->rental_session_time)
                                @if($res->court_id == $c->id)
                                    {{$res->handphone_number}}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($reservations as $res)
                            @if($res->rental_session_time == $ren->rental_session_time)
                                @if($res->court_id == $c->id)
                                    {{$res->court_name}}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($reservations as $res)
                            @if($res->rental_session_time == $ren->rental_session_time)
                                @if($res->court_id == $c->id)
                                    {{$res->date}}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($reservations as $res)
                            @if($res->rental_session_time == $ren->rental_session_time)
                                @if($res->payment_status == 'settlement' || 'capture')
                                    @if($res->court_id == $c->id)
                                        <p>Lunas</p>
                                    @endif
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($reservations as $res)
                            @if($res->rental_session_time == $ren->rental_session_time)
                                @if($res->court_id == $c->id)
                                    {{$res->payment_method}}
                                @endif
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($reservations as $res)
                            @if($res->rental_session_time == $ren->rental_session_time)
                                @if($res->court_id == $c->id)
                                    <a href="/admin/reservation/{{$res->id}}" type="submit" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('reservation.destroy', $res->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input onclick="return confirm('Are you sure you want delete reservation {{$res->id}} ?')" type="submit" class="btn btn-danger" value="DELETE">
                                    </form>
                                @endif
                            @endif
                        @endforeach
                    </td>
                </tr>
           @endforeach
        </tbody>
    </table>
@endforeach

</div>

<div class="modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Masukan Tanggal dan Nama Penyewa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/admin/reservation/available-courts" method="post">
            <label for="date">Tanggal</label>
            <input type="date">
            <label for="name">Nama Penyewa</label>
            <input type="text">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Lihat Ketersediaan Lapangan</button>
      </div>
    </div>
  </div>
</div>

@endsection



