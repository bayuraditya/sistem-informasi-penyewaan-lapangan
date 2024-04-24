@extends('layouts.app')
@section('content')
dashboard admin
<form id="logout-form" action="{{ route('logout') }}" method="POST">
@csrf
    <button type="submit" class="btn btn-danger">{{ __('Logout') }}</button>
</form> 
<br>
<a href="admin/reservation?date={{$today}}&court_id={{$court->id}}" class="btn btn-primary">Data Reservasi</a>
<a href="admin/court" class="btn btn-primary">Data Lapangan</a>
<a href="admin/profit" class="btn btn-primary">Data Keuntungan</a>
<a href="admin/transaction" class="btn btn-primary">Data Transaksi</a>

@endsection