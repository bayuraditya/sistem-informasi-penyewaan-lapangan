
@extends('layouts.app')

@section('content')
    <div class="m-5">
            <h1>Pilih Sesi</h1>
       <form action="/book" method="POST">
            @csrf
{{--
            <!-- @php
                $j=8;
            @endphp

            @for($i = 07; $i < 24; $i++)

            @php
            
            if($i < 10) {
                $m="$date 0$i:00:00";
            }else{ 
                $m="$date $i:00:00"; 
            }
            @endphp
            <input class="form-check-input" type="checkbox" value="{{$m}}" id="flexCheckDefault" name="start_time[]"
            @foreach($reservations as $r)
                @if($r->start_time == $m)
                    disabled
                @endif
            @endforeach
            >
            <label class="" for="">
                {{$i}}.00 - {{$j++}}.00
            </label>
            @endfor 
        
        --> --}}
            @php
            $j=7;
            @endphp

            @for($i=1;$i<=17;$i++)
            <input class="form-check-input" type="checkbox" value="{{$i}}" id="flexCheckDefault" name="rental_session_times[]"
            @foreach($reservations as $r)
                @if($r->rental_session_id == $i)
                    disabled
                @endif
            @endforeach
            >

            <label class="" for="">
                {{$j}}.00 - {{++$j}}.00
            </label>

            <br>
            @endfor
            <input type="text" class="visually-hidden" value="{{$court}}" name="court">
            <input type="text" class="visually-hidden" value="{{$date}}" name="date">
            <button type="submit" class="btn btn-primary">Book</button>

       </form>
    </div>
@endsection