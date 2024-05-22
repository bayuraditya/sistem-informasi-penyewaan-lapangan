@extends('layouts.admin-app')
@section('content')
<div class="m-5">
    <h1>Daftar User</h1>
    <br><br>
    <table class="table table-bordered">
        <thead>
            <tr>
                <td>Id</td>
                <td>Nama</td>
                <td>nomor HP</td>
                <td>Email</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            @foreach($allUser as $a)
            <tr>
                <td>{{$a->id}}</td>
                <td>{{$a->name}}</td>
                <td>{{$a->handphone_number}}</td>
                <td>{{$a->email}}</td>
                <td>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#transactionDetailModal_{{$a->id}}">
                    Riwayat Transaksi
                    </button>
                    <!-- Modal -->
                    <div class="modal modal-xl fade" id="transactionDetailModal_{{$a->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Riwayat Transaksi {{$a->name}}</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td>id transaksi</td>
                                            <td>total harga</td>
                                            <td>Payment Status</td>
                                            <td>metode pembayaran</td>
                                            <td>waktu pelunasan</td>
                                            <td>Detail reservasi</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($a->transactions as $t)
                                        <tr>
                                            <td>{{$t->id}}</td>
                                            <td>Rp{{$t->total_amount}}</td>
                                            <td>{{$t->payment_status}}</td>
                                            <td>{{$t->payment_method}}</td>
                                            <td>{{$t->settlement_time}}</td>
                                            <td> 
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reservationDetailModal_{{$t->id}}">
                                                    Detail Reservasi
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                              
                            </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@foreach($allUser as $a)
    @foreach($a->transactions as $t)
    <div class="modal fade" id="reservationDetailModal_{{$t->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">detail reservasi {{$a->name}} - {{$t->id}}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @foreach($t->reservations as $r)
                        <table>
                            <tr>
                                <td>{{$loop->iteration}}. </td>
                                <td>tanggal : {{$r->date}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>tanggal : {{$r->date}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>lapangan : {{$r->court_id}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>jam : {{$r->rental_session_id}}</td>
                            </tr>
                        </table>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" data-bs-target="#transactionDetailModal_{{$a->id}}" data-bs-toggle="modal">Kembali</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endforeach



@endsection