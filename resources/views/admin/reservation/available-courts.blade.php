
@extends('layouts.admin-app')
@section('style')
    <style>
        .btn-reservation {
            border-radius: 5px;
            cursor: pointer;
            border: 1px solid #4CAF50 !important;
        }

        .btn-check:checked+.btn {
            background-color: #4CAF50;
            color: white;
            border-color: #4CAF50;
        }

        .btn-check:checked+.btn p {
            color: white !important;
        }
    </style>
@endsection

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">




            <h1 class="mb-4 ">Pilih Sesi</h1>
        <form action="book" method="POST">
            @csrf

            @php
                $j = 7;
            @endphp

            @foreach ($allCourt as $ac)
                <h3 class="">Lapangan {{ $ac->court_name }}</h3>
                <img src="{{ asset('assets-user/img/facility.jpg') }}" alt="" class="img-fluid rounded-4 w-50 mb-5">
                <div class="row">
                    @foreach ($rentalSessions as $re)
                        <div class="col-4 col-md-3 col-lg-2 mb-3">
                            <div class="d-flex flex-column align-items-center">
                                <input
                                    @foreach ($unavailableReservations as $ur)
                                        @if ($ur->rental_session_id == $re->id && $ur->court_id == $ac->id)
                                            disabled
                                        @endif @endforeach
                                    type="checkbox" class="btn-check" name="reservation[{{ $ac->id }}][]"
                                    id="btn-check-outlined_{{ $ac->id }}_{{ $re->id }}"
                                    value="{{ $re->id }}">
                                <label style="width: 100%;" class="btn btn-reservation"
                                    for="btn-check-outlined_{{ $ac->id }}_{{ $re->id }}">
                                    <h6 class="m-0 " style="font-size: smaller;">60 menit</h6>
                                    <p class="m-0  ">{{ $re->rental_session_time }}</p>
                                    <h6 class="m-0 " style="font-size: smaller;">
                                        {{ 'Rp ' . number_format($re->price, 0, ',', '.') }}</h6>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                <br><br><br>
            @endforeach

            <input type="text" class="visually-hidden" value="{{ $date }}" name="date">
            <div class="d-grid gap-2 col-4 mx-auto">
                <button type="submit" class="btn btn-primary fw-bolder">Book</button>
            </div>
        </form>




            </div>
            <div class="card-body">

            </div>
        </div>
       
    </div>
@endsection
