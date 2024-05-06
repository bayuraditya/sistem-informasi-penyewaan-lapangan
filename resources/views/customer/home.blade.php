@extends('layouts.app')

@section('content')
<div class="m-5">

    <div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Profile
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="/profile">{{$user->name}}</a></li>
        <li><a class="dropdown-item" href="/profile/reservation-history">Reservation History</a></li>
        <li><a class="dropdown-item" href="/profile/transaction-history">Transaction History</a></li>
    </ul>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger">{{ __('Logout') }}</button>
    </form>
<br><br><br>
    <form method="GET" action="/available-courts">
        <div class="form-group w-25">
            <h4>BOOKING LAPANGAN</h4>
            <label for="date">Pilih Tanggal:</label>
            <input type="date" class="form-control " id="date" name="date">
            <br>
            <br>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>

</div>
   
@endsection