@extends('layouts.admin-app')
@section('content')
    <div class="col-12">
        <h3>Admin Dashboard</h3>
        <div class="row">
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="stats-icon blue">
                                    <i class="iconly-boldProfile"></i>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <h6 class="text-muted font-semibold">Total User</h6>
                                <h6 class="font-extrabold mb-0">{{$totalUser}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="stats-icon green">
                                    <i class="fa fa-money-bill"></i>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <h6 class="text-muted font-semibold">Total Transaksi </h6>
                                <h6 class="font-extrabold mb-0">{{$totalSettlementTransaction}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="stats-icon purple">
                                    <i class="fa fa-calendar-alt"></i>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <h6 class="text-muted font-semibold">Total Reservasi</h6>
                                <h6 class="font-extrabold mb-0">{{$totalReservation}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="stats-icon red">
                                    <i class="fa fa-cash-register"></i>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <h6 class="text-muted font-semibold">Total Keuntungan</h6>
                                <h6 class="font-extrabold mb-0">Rp{{$totalProfit}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h4>Transaksi Terbaru</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <!-- <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
                            <th>User</th>
                            <th>Total Bayar</th>
                            <th>Metode Pembayaran</th>
                            <th>Status</th>
                            <th>Waktu Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-bold-500">Michael Right</td>
                            <td>$15/hr</td>
                            <td class="text-bold-500">UI/UX</td>
                            <td>Remote</td>
                            <td>Austin,Taxes</td>
                            <td><a href="#"><i class="badge-circle badge-circle-light-secondary font-medium-1"
                                        data-feather="mail"></i></a></td>
                        </tr>
                        <tr>
                            <td class="text-bold-500">Morgan Vanblum</td>
                            <td>$13/hr</td>
                            <td class="text-bold-500">Graphic concepts</td>
                            <td>Remote</td>
                            <td>Shangai,China</td>
                            <td><a href="#"><i class="badge-circle badge-circle-light-secondary font-medium-1"
                                        data-feather="mail"></i></a></td>
                        </tr>
                        <tr>
                            <td class="text-bold-500">Tiffani Blogz</td>
                            <td>$15/hr</td>
                            <td class="text-bold-500">Animation</td>
                            <td>Remote</td>
                            <td>Austin,Texas</td>
                            <td><a href="#"><i class="badge-circle badge-circle-light-secondary font-medium-1"
                                        data-feather="mail"></i></a></td>
                        </tr>
                        <tr>
                            <td class="text-bold-500">Ashley Boul</td>
                            <td>$15/hr</td>
                            <td class="text-bold-500">Animation</td>
                            <td>Remote</td>
                            <td>Austin,Texas</td>
                            <td><a href="#"><i class="badge-circle badge-circle-light-secondary font-medium-1"
                                        data-feather="mail"></i></a></td>
                        </tr>
                        <tr>
                            <td class="text-bold-500">Mikkey Mice</td>
                            <td>$15/hr</td>
                            <td class="text-bold-500">Animation</td>
                            <td>Remote</td>
                            <td>Austin,Texas</td>
                            <td><a href="#"><i class="badge-circle badge-circle-light-secondary font-medium-1"
                                        data-feather="mail"></i></a></td>
                        </tr>
                    </tbody>
                </table> -->
                <table class="table table-bordered" id="">
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
                                        <p class="badge text-bg-warning">
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <a href="admin/transaction">selengkapnya...</a>
            </div>
        </div>
    </div>
@endsection
