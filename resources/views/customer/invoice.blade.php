@extends('layouts.checkout-app')

@section('content')
<div class="m-5">
   <h1>INVOICE</h1>
   <div>
    {{$transaction->id}} <br>
    {{$transaction->payment_status}} <br>
    <a href="../home" class="btn btn-primary">home</a>
   </div>
</div>
@endsection