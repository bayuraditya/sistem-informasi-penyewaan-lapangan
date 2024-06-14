@extends('layouts.guest-app')
@section('content')
<div class="m-5">

    <h1>
        Guest 

    </h1>
    <br>
    
    login 
    <a href="{{ route('login') }}" class="btn btn-primary">{{ __('Login') }}</a>
    <br>
    register
    <a href="{{ route('register') }}" class="btn btn-success">{{ __('Register') }}</a>
    
</div>
@endsection