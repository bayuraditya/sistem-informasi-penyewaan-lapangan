@extends('layouts.app')
@section('content')
<div class="m-5">
    <form id="logout-form" action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger">{{ __('Logout') }}</button>
    </form>
<br><br><br>
    <form method="GET" action="/available-courts">
        <div class="form-group w-25">
            <h4>BOOKING LAPANGAN</h4>
            <label for="date">Pilih Tanggal:</label>
            <input type="date" class="form-control " id="date" name="date" value="{{$todayDate}}">
            <br>
            <br>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
   
@endsection