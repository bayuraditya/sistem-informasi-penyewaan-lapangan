@extends('layouts.app')

@section('content')
<br><br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Ganti Password</div>

                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form method="POST" action="/profile/change-password/{{$user->id}}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="current_password">Password Lama</label>
                                <input id="current_password" type="password" class="form-control" name="current_password" required>
                            </div>

                            <div class="form-group">
                                <label for="password">Password Baru</label>
                                <input id="password" type="password" class="form-control" name="password" required>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password Baru</label>
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                            </div>
<br>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
