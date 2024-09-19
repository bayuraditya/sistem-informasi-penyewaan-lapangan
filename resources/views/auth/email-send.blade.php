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
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-lg-4 col-12">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header text-center text-white">
                        <h4 class="mb-0">Lupa Password</h4>
                    </div>

                    <div class="card-body">
                       link reset password sudah dikirimkan ke email anda, mohon cek inbox email anda. 
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function myFunction() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
@endsection
