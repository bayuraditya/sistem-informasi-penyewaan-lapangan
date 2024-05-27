
@extends('layouts.export-app')

@section('content')
<div class="m-5">

    <h1>Data Reservasi</h1>
    <!-- 
    tanggal (default nya today)
    lapangan/court
    tabel : tanggal, court, nama, sesi, payment status, 

     -->
    
  <!-- tanggal dan court -->
   

<br>
Tanggal : {{$date}} <br>
Lapangan : {{$court->court_name}}
<br> <br>
<style>
    table, th, td {
  border: 1px solid;
  border-collapse: collapse;
}
</style>
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



