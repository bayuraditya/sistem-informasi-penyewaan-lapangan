@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 100px; margin-bottom: 100px">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="text-center mb-5">Ubah Kata Sandi</h3>
                        <p>Ubah password anda </p>
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="/profile/change-password/{{ $user->id }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-3">
                                <label for="current_password" class="form-label">Kata Sandi Lama</label>
                                <input id="current_password" type="password" class="form-control" name="current_password"
                                    required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="new_password" class="form-label">Kata Sandi Baru</label>
                                <input id="new_password" type="password"
                                    class="form-control @error('new_password') is-invalid @enderror" name="new_password"
                                    required autocomplete="new-password">
                                @error('new_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="new_password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                                <input id="new_password_confirmation" type="password" class="form-control"
                                    name="new_password_confirmation" required autocomplete="new-password">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
