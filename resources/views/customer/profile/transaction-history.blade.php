
@extends('layouts.app')

@section('content')
<div class="m-5">
    <a href="/home" class="btn btn-primary">HOME</a> <br><br>
    <h2>Data Transaksi</h2>
    <br>


    <br><br>
    <table class="table table-bordered">
        <thead>
            <tr>
                <td>No</td>
                <td>Id Transaksi</td>
                <td>Total Bayar</td>
                <td>Metode Pembayaran</td>
                <td>Status Pembayaran</td>
                <td>Waktu transaksi</td>
                <td>Waktu Transaksi Lunas</td>
                <td>Detail Pesanan</td>
                
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $tr)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$tr->id}}</td>
                
                <td>Rp{{$tr->total_amount}}</td>
                <td>{{$tr->payment_method}}</td>
                <td>
                    @if($tr->payment_status == 'settlement' || $tr->payment_status == 'capture')
                        <p class="text-success fw-bold">
                            Lunas
                        </p>
                    @elseif($tr->payment_status == 'expire')
                        <p class="text-danger fw-bold">
                            Transaksi Dibatalkan
                        </p>
                    @elseif($tr->payment_status == 'pending')
                        <p class="badge text-bg-warning">
                            Pending
                        </p>
                    @else
                        <p class="badge text-bg-seccondary">
                            {{$tr->payment_status}}
                        </p>
                    @endif    
                </td>
                <td>{{$tr->transaction_time}}</td>
                <td>{{$tr->settlement_time}}</td>
                <td>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#orderDetailModal_{{$tr->id}}">
                    Detail Reservasi
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


<!-- Modal -->

@foreach($transactions as $tr)
<div class="modal fade" id="orderDetailModal_{{$tr->id}}" tabindex="-1" aria-labelledby="orderDetailModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Order no {{$tr->id}}</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- order detail here
        lapangan jam 
        -->
      
    <table>
        <tbody>
                @foreach($tr->reservations as $ts)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td> Lapangan</td>
                    <td> : {{$ts->court->court_name}}</td>
                    
                </tr>
                <tr>
                    <td></td>
                    <td> Jam</td>
                <td> : {{$ts->rentalSession->rental_session_time}}</td>
                
            </tr>
            @endforeach
        </tbody>
    </table>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endforeach

@endsection