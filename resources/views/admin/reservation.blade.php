
@extends('layouts.app')

@section('content')
<div class="m-5">
    <!-- 
    tanggal (default nya today)
    lapangan/court
    tabel : tanggal, court, nama, sesi, payment status, 

     -->
     <td data-values='{"id1": "value1", "id2": "value2"}'>Data</td>
    {{$data-values}}
     <form action="" method="">
        <input type="date" name="" id="">

     </form>

     <table class="table table-bordered">
        <th>
            <tr>
                <td>Nomor</td>
                <td>sesi</td>
                <td>nama</td>
                <td>lapangan</td>
                <td>tanggal</td>
                <td>status pembayaran</td>
            </tr>
        </th>
        <tbody>
         
        </tbody>
    </table>
</div>
    {{dd($reservation)}}
    {{}}
@endsection