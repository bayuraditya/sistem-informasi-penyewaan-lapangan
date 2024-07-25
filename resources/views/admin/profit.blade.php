@extends('layouts.admin-app')

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h2>Keuntungan Total : {{ 'Rp ' . number_format($totalProfit, 0, ',', '.') }}</h2>
            </div>
            <div class="card-body">
                <h3>Cari Data Keuntungan</h3>
                <div class="row">
                    <div class="col-md-4 col-12">
                        <form action="/admin/profit" method="get">
                            <div class="form-group">
                                <label for="court_name" class="form-label">pilih tanggal awal</label>
                                <input type="date" class="form-control" id="startDate" name="startDate"><br>
                                <label for="court_name" class="form-label">pilih tanggal akhir</label>
                                <input type="date" class="form-control" id="endDate" name="endDate"><br>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-8 col-12">
                        <h4>Tanggal Awal : {{ $startDate }}</h4>
                        <h4>Tanggal Akhir : {{ $endDate }}</h4>
                        <h4>Total Booking : {{ $filteredTotalBookedSession }} jam</h4>
                        <h4>Keuntungan : {{ 'Rp ' . number_format($filteredProfit, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
