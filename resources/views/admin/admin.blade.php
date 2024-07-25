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
                                <h6 class="font-extrabold mb-0">183.000</h6>
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
                                <h6 class="text-muted font-semibold">Total Transaksi</h6>
                                <h6 class="font-extrabold mb-0">Rp. 80.000</h6>
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
                                <h6 class="font-extrabold mb-0">80.000</h6>
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
                                <h6 class="font-extrabold mb-0">Rp. 80.000</h6>
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
                <table class="table table-bordered mb-0">
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
                </table>
            </div>
        </div>
    </div>
@endsection
