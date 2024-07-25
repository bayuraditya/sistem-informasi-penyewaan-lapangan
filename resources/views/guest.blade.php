@extends('layouts.app')
@section('content')
    <div class="hero-section" style="">
        <div class="container">
            <form method="GET" action="{{ route('availableCourts') }}">
                <h2 class="text-center">BOOKING LAPANGAN</h2><br>
                <div class="py-3  mx-auto form-group text-dark bg-white d-flex justify-content-center rounded-pill ">
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
@endsection
