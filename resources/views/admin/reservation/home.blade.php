@extends('layouts.admin-app')

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">


            <h1>Tambah Reservasi</h1>
            </div>
            <div class="card-body">
             
            <form method="GET" action="available-courts">
                <div class="">
                    <label for="date" class="my-auto py-auto m-2">Pilih Tanggal:</label>
                    <div class="d-inline-flex m-2">
                        <input type="date" class="form-control d-inline-flex" id="date" name="date">
                    </div>
                    <button type="submit" class="m-2 px-4 rounded-pill btn text-light"
                        style="background-color: #4CAF50;">Cari</button>
                </div>
            </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                 </div>
        </div>
    </div>

    <!-- <div class="modal" tabindex="-1">
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
    </div> -->
@endsection
