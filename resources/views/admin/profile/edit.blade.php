@extends('layouts.admin-app')
@section('content')

<div class="m-5">

@if(session('success'))
    <div class="alert-success alert  alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

  <form action="/admin/profile/update/{{$user->id}}" method="post">
    @csrf
    @method('PUT')
    <h1>Edit Profile</h1>
    <div class="mb-3">
      <label for="user_name" class="form-label">Nama </label>
      <input type="text" class="form-control" id="user_name" name="name" value="{{$user->name}}">
    </div>
    <div class="mb-3">
      <label for="user_name" class="form-label">Email</label>
      <input type="text" class="form-control" id="email" name="email" value="{{$user->email}}">
    </div>
    <div class="mb-3">
      <label for="user_name" class="form-label">Nomor Handphone</label>
      <input type="number" class="form-control" id="handphone_number" name="handphone_number" value="{{$user->handphone_number}}">
    </div>
  <button type="submit" class="btn btn-success">Simpan Perubahan</button>
</form>
<br><br>  
<a href="/admin/profile/change-password/">Ubah Kata Sandi</a>


</div>

@endsection