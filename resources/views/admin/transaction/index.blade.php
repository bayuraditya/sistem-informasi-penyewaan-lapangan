@extends('layouts.admin-app')
@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h2>Data Transaksi</h2>
                <br>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exportTransaction">
                    Cetak
                </button>
            </div>
            <div class="card-body">
            @if (session('success'))
                    <div class="alert-success alert  alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <table class="table table-bordered" id="table1">
                    <thead>
                        <tr>
                            <td>No</td>
                            <td>Id Transaksi</td>
                            <td>User</td>
                            <td>Total Bayar</td>
                            <td>Metode Pembayaran</td>
                            <td>Status Pembayaran</td>
                            <td>waktu pesanan dibuat</td>
                            <td>Waktu transaksi</td>
                            <td>Waktu Transaksi Lunas</td>
                            <td>Detail Pesanan</td>
                            <td>Action</td>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $tr)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $tr->id }}</td>
                                <td>
                                    <a href="/admin/user/{{ $tr->user_id }}">{{ $tr->user->name }}</a>
                                </td>
                                <td> {{ 'Rp ' . number_format($tr->total_amount, 0, ',', '.') }}</td>
                                <td>{{ $tr->payment_method }}</td>
                                <td>
                                    @if ($tr->payment_status == 'settlement' || $tr->payment_status == 'capture')
                                        <p class="text-success fw-bold">
                                            Lunas
                                        </p>
                                    @elseif($tr->payment_status == 'expire')
                                        <p class="text-danger fw-bold">
                                            Transaksi Dibatalkan
                                        </p>
                                    @elseif($tr->payment_status == 'pending')
                                        <p class="text-warning">
                                            Pending
                                        </p>
                                    @else
                                        <p class="badge text-bg-seccondary">
                                            {{ $tr->payment_status }}
                                        </p>
                                    @endif
                                </td>
                                <td>{{ $tr->created_at }}</td>
                                <td>{{ $tr->transaction_time }}</td>
                                <td>{{ $tr->settlement_time }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#orderDetailModal_{{ $tr->id }}">
                                        Detail Reservasi
                                    </button>
                                </td>
                                <td>
                                      
                                                    <form action="{{ route('transaction.destroy',['id' => $tr->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input
                                                            onclick="return confirm('Are you sure you want delete transaction {{ $tr->id }} ?')"
                                                            type="submit" class="btn btn-danger" value="DELETE">
                                                    </form>
                                               
                                    </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal Print Transaction-->
    <div class="modal fade" id="exportTransaction" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Cetak Data Transaksi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/admin/transaction/export" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Pilih Tanggal Awal</label>
                            <input type="date" class="form-control" name="start_date" id=""
                                value="{{ $today }}">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Pilih Tanggal Akhir</label>
                            <input type="date" class="form-control" name="end_date" id=""
                                value="{{ $today }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Cetak</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Order Detail-->

    @foreach ($transactions as $tr)
        <div class="modal fade" id="orderDetailModal_{{ $tr->id }}" tabindex="-1" aria-labelledby="orderDetailModal"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Order no {{ $tr->id }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @foreach ($tr->reservations as $ts)
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td><strong>Tanggal</strong></td>
                                        <td>{{ $ts->date }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Lapangan</strong></td>
                                        <td>{{ $ts->court_id }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jam</strong></td>
                                        <td>{{ $ts->rentalSession->rental_session_time }}</td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
