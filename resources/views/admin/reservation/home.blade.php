@extends('layouts.admin-app')

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            <h1>Tambah Reservasi</h1>
            </div>
            <div class="card-body">
             
            <form method="GET" action="available-courts">
                <div class="">
                    <label for="date" class="my-auto py-auto m-2">Pilih Tanggal:</label>
                    <div class="d-inline-flex m-2">
                        <input type="date" class="form-control d-inline-flex" id="date" name="date">
                    </div>
                    <div class="form-check mb-4">
                
                <input class="form-check-box" type="radio" checked id="regular" name="memberCategory" value="regular">
                <label class="form-check-label" for="regular" >Umum</label><br>

                <input class="form-check-box" type="radio" id="member"  name="memberCategory" value="member">
                <label class="form-check-label" for="member">Member</label><br>
            </div>
                    <button type="submit" class="m-2 px-4 rounded-pill btn text-light"
                        style="background-color: #4CAF50;">Cari</button>
                </div>
            </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                 </div>
        </div>
    </div>
@endsection
