@extends('layouts.admin-app')
@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h1>Edit Lapangan</h1>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert-success alert  alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="/admin/court/{{ $court->id }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="court_name" class="form-label">Nama Lapangan</label>
                        <input type="text" class="form-control" id="court_name" name="court_name"
                            value="{{ $court->court_name }}">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" rows="3" name="description" >{{ $court->description }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
