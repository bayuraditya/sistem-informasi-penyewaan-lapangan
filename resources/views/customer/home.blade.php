@extends('layouts.app')
@section('content')
<style>
        .hero-section {
            background: url('https://images.unsplash.com/photo-1626721105368-a69248e93b32?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') no-repeat center center;
            background-size: cover;
            height: 100vh;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
    </style>
    
  <!-- Hero Section -->
  <div class="hero-section">
        <div class="container">
            <form method="GET" action="/available-courts">
                <h2>BOOKING LAPANGAN</h2><br>
                <div class="py-3  mx-auto form-group text-dark bg-white d-flex justify-content-center rounded-pill ">
                    <label for="date" class="my-auto py-auto m-2">Pilih Tanggal:</label> 
                    <div class="d-inline-flex m-2"> 
                        <input type="date" class="form-control d-inline-flex" id="date" name="date" value="{{$todayDate}}">
                    </div>
                    <button type="submit" class="m-2 px-4 rounded-pill btn btn-primary ">Cari</button>
                </div>
            </form>
            <!-- <h1 class="display-4">Welcome to Forever Barbershop</h1>
            <p class="lead">The best place for a modern and stylish haircut.</p>
            <a class="btn btn-primary btn-lg" href="#" role="button">Book Now</a> -->
        </div>
  </div>
<div class="m-5">
    <form id="logout-form" action="{{ route('logout') }}" method="POST">
        @csrf
    </form>
<br><br><br>
    
</div>
   
@endsection