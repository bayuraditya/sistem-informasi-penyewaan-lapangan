@extends('layouts.app')
@section('content')
    <div class="container" style="margin-top: 100px; margin-bottom: 100px;">
        <h3 class="text-center">Harga</h3>
        <p class="text-center">Harga untuk penyewaan lapangan dengan 2 tipe harga</p>
        <div class="d-flex align-items-stretch justify-content-center flex-column flex-lg-row gap-5 mt-5">
            <div class="card border-primary w-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-center">UMUM</h5>
                    <div class="card-text d-flex flex-column justify-content-center align-items-center flex-grow-1">
                        <ul class="fs-5 text-center">
                            <li>07.00 - 15.00 : Rp. 40.000 / Jam</li>
                            <li>15.00 - 22.00 : Rp. 50.000 / Jam</li>
                        </ul>
                    </div>
                    <a href="{{ route('home') }}" class="btn btn-primary w-100 mt-auto">Pesan Sekarang</a>
                </div>
            </div>
            <div class="card border-primary w-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-center">MEMBER</h5>
                    <div class="card-text d-flex flex-column justify-content-center align-items-center flex-grow-1">
                        <ul class="fs-5 text-center">
                            <li>07.00 - 15.00 : Rp. 35.000 / Jam</li>
                            <li>15.00 - 22.00 : Rp. 45.000 / Jam</li>
                        </ul>
                        <small class="mb-4">* Minimal 8 jam/bulan</small>
                    </div>
                    <a href="https://wa.me/+6287869710978?text=halo, saya ingin membuat member" class="btn btn-primary w-100 mt-auto">Pesan Sekarang</a>
                </div>
            </div>
        </div>
    </div>
@endsection
