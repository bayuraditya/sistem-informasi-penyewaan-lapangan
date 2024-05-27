@extends('layouts.export-app')

@section('content')
<div class="m-5">
    <h2>Data Transaksi</h2>
    <br>
<p>

tanggal awal : {{$startDate}} <br>
tanggal akhir : {{$endDate}}

</p>
<style>
    table, th, td {
  border: 1px solid;
  border-collapse: collapse;
}
</style>
    <br><br>
    <table class="table table-bordered">
        <thead>
            <tr>
                <td>No</td>
                <td>Id Transaksi</td>
                <td>User</td>
                <td>Total Bayar</td>
                <td>Metode Pembayaran</td>
                <td>Status Pembayaran</td>
                <td>Waktu transaksi</td>
                <td>Waktu Transaksi Lunas</td>
                <td>created at</td>
                
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $tr)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$tr->id}}</td>
                <td>
                <a href="/admin/user/{{$tr->user_id}}">{{$tr->user->name}}</a>    
                </td>
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
                <td>{{$tr->created_at}}</td>
                
            </tr>
            @endforeach
        </tbody>
    </table>
</div>



@endsection