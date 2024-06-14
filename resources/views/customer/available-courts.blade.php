
@extends('layouts.app')

@section('content')
<!-- <br><br><br> -->
    <div class="m-5">
            <h1>Pilih Sesi</h1>
       <form action="/book" method="POST">
            @csrf
          
            @php
            $j=7;
            @endphp
            
            @foreach($allCourt as $ac)
                <h3>Lapangan  {{$ac->court_name}}</h3>
                @foreach($rentalSessions as $re)
                    <div class="d-inline-flex ">
                        <input
                            @foreach($unavailableReservations as $ur)
                                @if($ur->rental_session_id == $re->id && $ur->court_id == $ac->id)
                                    disabled
                                @endif
                            @endforeach

                        type="checkbox" class="btn-check" name="reservation[{{$ac->id}}][]"  id="btn-check-outlined_[{{$ac->id}}][]_{{$re->id}}" value="{{$re->id}}" >
                        <label style="width: 120px;" class="btn btn-outline-info border border-primary m-1"  for="btn-check-outlined_[{{$ac->id}}][]_{{$re->id}}" >
                            <p class="m-0 text-secondary" style="font-size: smaller;"> 60 menit </p> 
                            <p class="m-0 text-dark fw-semibold"> {{$re->rental_session_time}}</p>  
                            <p class="m-0 text-muted" style="font-size: smaller;"> Rp{{$re->price}} </p>
                        </label>
                        <br>
                    </div>
                @endforeach
                <br><br><br>
            @endforeach

            <input type="text" class="visually-hidden" value="{{$date}}" name="date">
            <div class="d-grid gap-2 col-4 mx-auto">
                <button type="submit" class="btn btn-primary fw-bolder">Book</button>
            </div>

       </form>
    </div>
@endsection