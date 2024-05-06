@extends('layouts.app')
@section('content')
<div class="m-5">
    <h1>Detail User</h1>
    <table>
        
        <tr>
            <td>Nama</td>
            <td> : </td>
            <td>{{$user->name}}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td> : </td>
            <td> {{$user->email}} </td>
        </tr>
        <tr>
            <td>No HP</td>
            <td> : </td>
            <td> {{$user->handphone_number}} </td>
        </tr>
    </table>
    riwayat Reservasi
    riwayat transaksi
</div>
@endsection