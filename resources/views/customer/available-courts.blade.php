
@extends('layouts.app')

@section('content')
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
                    <input
                        @foreach($unavailableReservations as $ur)
                            @if($ur->rental_session_id == $re->id && $ur->court_id == $ac->id)
                                disabled
                            @endif
                        @endforeach

                    type="checkbox" class="form-check-input" name="reservation[{{$ac->id}}][]" id="" value="{{$re->id}}" >
                    <label for="">{{$re->rental_session_time}}</label>
                      <br>

                @endforeach
                <br><br><br>
            @endforeach

            <input type="text" class="visually-hidden" value="{{$date}}" name="date">
            <button type="submit" class="btn btn-primary">Book</button>

       </form>
    </div>
@endsection