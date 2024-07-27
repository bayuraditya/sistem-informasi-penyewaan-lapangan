@extends('layouts.admin-app')

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Data Reservasi</h4>
                <p>
                    User : {{ $user->name }}
                    <br>
                    Tanggal : {{ $date }}
                </p>
            </div>
            <div class="card-body">
                <a href="/admin/reservation/select-date" class="btn btn-primary">Tambah Reservasi</a>
                <div class="row mb-5">
                    <div class="col-lg-6 col-12">
                        <form action="reservation" class="mt-5" method="get">
                            <div class="col-12">
                                <label for="date">Pilih Tanggal</label>
                                <input type="date" class="form-control" name="date" id=""
                                    value="{{ $today }}">
                            </div>
                            <br>
                            <input type="submit" value="Submit" class="btn btn-primary">
                        </form>
                        <form action="/admin/reservation/export?date={{ $date }}&court_id={{ $court->id }}"
                            method="post" class="mt-5">
                            @csrf
                            <input type="date" class="d-none" name="date" value="{{ $date }}" id="">
                            <input type="text" class="d-none" name="court_id" value="{{ $court->id }}" id="">
                            <button type="submit" class="btn btn-primary">Cetak Reservasi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                @foreach ($allCourt as $c)
                    <h2>Lapangan : {{ $c->court_name }}</h2>
                    <table class="table table-bordered" id="table2">
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
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($rentalSession as $ren)
                                <tr>
                                    <td>{{ $ren->id }}</td>
                                    <td>{{ $ren->rental_session_time }}</td>
                                    <td>
                                        @foreach ($reservations as $res)
                                            @if ($res->rental_session_time == $ren->rental_session_time)
                                                @if ($res->court_id == $c->id)
                                                    {{ $res->name }}
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($reservations as $res)
                                            @if ($res->rental_session_time == $ren->rental_session_time)
                                                @if ($res->court_id == $c->id)
                                                    {{ $res->handphone_number }}
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($reservations as $res)
                                            @if ($res->rental_session_time == $ren->rental_session_time)
                                                @if ($res->court_id == $c->id)
                                                    {{ $res->court_name }}
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($reservations as $res)
                                            @if ($res->rental_session_time == $ren->rental_session_time)
                                                @if ($res->court_id == $c->id)
                                                    {{ $res->date }}
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($reservations as $res)
                                            @if ($res->rental_session_time == $ren->rental_session_time)
                                                @if ($res->payment_status == 'settlement' || 'capture')
                                                    @if ($res->court_id == $c->id)
                                                        <p>Lunas</p>
                                                    @endif
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($reservations as $res)
                                            @if ($res->rental_session_time == $ren->rental_session_time)
                                                @if ($res->court_id == $c->id)
                                                    {{ $res->payment_method }}
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($reservations as $res)
                                            @if ($res->rental_session_time == $ren->rental_session_time)
                                                @if ($res->court_id == $c->id)
                                                    <a href="/admin/reservation/edit/{{ $res->reservation_id }}" type="submit"
                                                        class="btn btn-warning">Edit</a>
                                                    <form action="{{ route('reservation.destroy', $res->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input
                                                            onclick="return confirm('Are you sure you want delete reservation {{ $res->id }} ?')"
                                                            type="submit" class="btn btn-danger" value="DELETE">
                                                    </form>
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
        </div>
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
