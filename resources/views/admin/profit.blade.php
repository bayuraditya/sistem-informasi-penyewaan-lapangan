
@extends('layouts.admin-app')

@section('content')
<div class="m-5">
    <h2>Keuntungan Total : Rp{{$totalProfit}}</h2>
<br>
    <h3>Cari Data Keuntungan</h3>
    <form action="/admin/profit" method="get">
        <div class="form-group w-25">
            <label for="court_name" class="form-label">pilih tanggal awal</label>
            <input type="date" class="form-control" id="startDate" name="startDate"><br>
            <label for="court_name" class="form-label">pilih tanggal akhir</label>
            <input type="date" class="form-control" id="endDate" name="endDate"><br>
            <button type="submit" class="btn btn-primary">Submit</button>

        </div>
    </form>
<br>
    <!-- 
        tanggal awal :
        tanggal akhir :
        keuntungan :
     -->
     <h4>Tanggal Awal : {{$startDate}}</h4>
     <h4>Tanggal Akhir : {{$endDate}}</h4>
     <h4>Total Booking : {{$filteredTotalBookedSession}} jam</h4>
     <h4>Keuntungan : Rp{{$filteredProfit}}</h4>
</div>
@endsection