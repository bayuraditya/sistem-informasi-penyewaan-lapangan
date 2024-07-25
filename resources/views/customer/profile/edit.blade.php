@extends('layouts.app')

@section('style')
    <style>
        #profil-user {
            margin-top: 100px;
            margin-bottom: 100px;
        }
    </style>
@endsection

@section('content')
    <div class="container" id="profil-user">
        <div class="my-5">
            @if (session('success'))
                <div class="alert-success alert  alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-4 mb-5">
                    <div class="card" style="width: 18rem;">
                        <img src="https://ui-avatars.com/api/?name={{ $user->name }}&background=random"
                            class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">{{ $user->name }}</h5>
                            <p class="card-text">{{ $user->email }}</p>
                            <p class="card-text">{{ $user->handphone_number }}</p>

                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <form action="/profile/update/{{ $user->id }}" class="mb-5" method="post">
                        @csrf
                        @method('PUT')
                        <h1>Edit Profile</h1>
                        <div class="mb-3">
                            <label for="user_name" class="form-label">Nama </label>
                            <input type="text" class="form-control" id="user_name" name="name"
                                value="{{ $user->name }}">
                        </div>
                        <div class="mb-3">
                            <label for="user_name" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" name="email"
                                value="{{ $user->email }}">
                        </div>
                        <div class="mb-3">
                            <label for="user_name" class="form-label">Nomor Handphone</label>
                            <input type="number" class="form-control" id="handphone_number" name="handphone_number"
                                value="{{ $user->handphone_number }}">
                        </div>
                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                    </form>
                    <a href="/profile/change-password/" class="text-decoration-none btn btn-outline-primary">Ubah Kata
                        Sandi</a>
                </div>
            </div>
        </div>
    </div>
@endsection
