@extends('layouts.app')

@section('content')
    <div class="m-5">
            
       <form action="/book" method="POST">
            @csrf

            @php
                $j=8;
               
            @endphp

            @for($i = 07; $i < 24; $i++)

            @php
                $k=10;
               
          

            if($i < 10) {
                $m="$date 0$i:00:00";
            }else{ 
                $m="$date $i:00:00"; 
            }
            @endphp
            <input class="form-check-input" type="checkbox" value="{{$m}}" id="flexCheckDefault" 
            @foreach($reservations as $r)
                @if($r->start_time == $m)
                    disabled
                @endif
            @endforeach
            >
            <label class="form-check-label" for="flexCheckDefault">
                {{$i}}.00 - {{$j++}}.00
            </label>
            
            <br>
            @endfor

            <button type="submit" class="btn btn-primary">Book</button>

       </form>
    </div>
@endsection