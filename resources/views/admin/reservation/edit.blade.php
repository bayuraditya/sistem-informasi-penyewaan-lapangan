@extends('layouts.admin-app')
@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h1>Edit Reservasi</h1>
                <!-- 
                id reservasi (disable)
                tgl
                sesi-jam
                lapangan
                
                user


                -->
            </div>
            @foreach($reservations as $res)

            {{$res->reservation_id}}
            {{$res->court_id}}
            {{$res->rental_session_id}}
            @endforeach
            <div class="card-body">
                @if (session('success'))
                    <div class="alert-success alert  alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="/admin/court/" method="post">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="court_name" class="form-label">Id Reservasi</label>
                        <input type="text" class="form-control" id="court_name" name="court_name"
                            value="" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" rows="3" name="description" value=""></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
