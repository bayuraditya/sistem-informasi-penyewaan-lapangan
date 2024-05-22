@extends('layouts.admin-app')

@section('content')
<!-- 
    dashborad 
        history transaksi
            data transaksi, ada detail reservasi lapangan(modal)
        history reservasi
            data reservasi, ada detail transaksi (modal)

-->
{{dd($reservations)}}
@endsection
