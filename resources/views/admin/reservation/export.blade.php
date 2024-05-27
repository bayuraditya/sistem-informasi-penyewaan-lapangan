
@extends('layouts.export-app')


@section('content')
<style>
    table, th, td {
  border: 1px solid;
  border-collapse: collapse;
}
</style>
<div class="m-5">

<p>Tanggal : {{$date}}</p>

<br><br>
@foreach($allCourt as $c)
    <h2>Lapangan : {{$c->court_name}}</h2>
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
                    
                </tr>
           @endforeach
        </tbody>
    </table>
@endforeach

</div>



@endsection



