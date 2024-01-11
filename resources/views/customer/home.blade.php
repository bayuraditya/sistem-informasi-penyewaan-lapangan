@extends('layouts.app')

@section('content')
<div class="m-5">
    <h1> 
HOME CUSTOMER

</h1>
<form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">{{ __('Logout') }}</button>
                    </form>

</div>

                <!-- 
                    home - cek lapangan pilih tanggal,court - hasil cek lapangan, pilih jam - review order - pay - invoice
                 -->

                 <div class="m-5 w-s">
                    <form method="GET" action="/available-courts">
                        <div class="form-group">
                            <label for="date">Pilih Tanggal:</label>
                            <input type="date" class="form-control" id="date" name="date">
                            <br>
                            <label for="court">Pilih Lapangan:</label>
                            <select id="court" name="court" class="form-select" aria-label="Default select example">
                                @foreach($court as $court)
                                    <option value="{{$court->id}}">{{$court->court_name}}</option>
                                @endforeach
                                
                            </select>
                            <button type="submit" class="btn btn-primary">Submit</button>

                        </div>
                        
                    </form>
   
                 @endsection