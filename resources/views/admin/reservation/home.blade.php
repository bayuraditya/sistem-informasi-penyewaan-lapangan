@extends('layouts.app')
@section('content')
<div class="m-5">
   
<br><br><br>
    <form method="GET" action="available-courts">
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