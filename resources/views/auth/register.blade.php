@extends('layouts.guest-app')

@section('style')
    <style>
        body {
            background-color: #f5f5f5;
        }

        .card {
            background: rgba(255, 255, 255, 0.9);
        }

        .card-header {
            background: linear-gradient(45deg, #007bff, #00c6ff);
        }

        .btn-primary {
            background: linear-gradient(45deg, #007bff, #00c6ff);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #0056b3, #0096c7);
        }

        .form-check-label {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="mt-5 row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-header text-center bg-primary text-white">Daftar</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="handphone_number" class="form-label">Nomor Handphone</label>
                                <input id="handphone_number" type="number"
                                    class="form-control @error('handphone_number') is-invalid @enderror"
                                    name="handphone_number" value="{{ old('handphone_number') }}" required
                                    autocomplete="handphone_number">
                                @error('handphone_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Kata Sandi</label>
                                <div class="input-group">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="new-password">
                                    <span class="input-group-text" onclick="togglePassword()" style="cursor: pointer;">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                                <script>
                                    function togglePassword() {
                                        var x = document.getElementById("password");
                                        var eyeIcon = document.querySelector('#password + .input-group-text .fa');
                                        if (x.type === "password") {
                                            x.type = "text";
                                            eyeIcon.classList.remove('fa-eye');
                                            eyeIcon.classList.add('fa-eye-slash');
                                        } else {
                                            x.type = "password";
                                            eyeIcon.classList.remove('fa-eye-slash');
                                            eyeIcon.classList.add('fa-eye');
                                        }
                                    }
                                </script>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password-confirm" class="form-label">Konfirmasi Kata Sandi</label>
                                <div class="input-group">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" required autocomplete="new-password">
                                    <span class="input-group-text" onclick="togglePasswordConfirm()"
                                        style="cursor: pointer;">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                                <script>
                                    function togglePasswordConfirm() {
                                        var x = document.getElementById("password-confirm");
                                        var eyeIcon = document.querySelector('#password-confirm + .input-group-text .fa');
                                        if (x.type === "password") {
                                            x.type = "text";
                                            eyeIcon.classList.remove('fa-eye');
                                            eyeIcon.classList.add('fa-eye-slash');
                                        } else {
                                            x.type = "password";
                                            eyeIcon.classList.remove('fa-eye-slash');
                                            eyeIcon.classList.add('fa-eye');
                                        }
                                    }
                                </script>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    {{ __('Register') }}
                                </button>
                            </div>
                            <div class="mt-3 text-center">
                                <p>Sudah punya akun? <a href="/login">Login</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
