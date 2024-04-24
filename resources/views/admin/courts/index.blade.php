@extends('layouts.app')
@section('content')

<div class="m-5">
<a href="/admin" class="btn btn-primary">HOME</a> <br><br>

@if(session('success'))
    <div class="alert-success alert  alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
  <form action="court/store" method="post">
    @csrf
    <h1>Tambah Lapangan</h1>
    <div class="mb-3">
      <label for="court_name" class="form-label">Nama Lapangan</label>
      <input type="text" class="form-control" id="court_name" name="court_name">
    </div>
    <div class="mb-3">
      <label for="exampleFormControlTextarea1" class="form-label">Deskripsi</label>
      <textarea class="form-control" id="description" rows="3" name="description"></textarea>
    </div> 
  <button type="submit" class="btn btn-primary">Tambahkan</button>
</form>
<br><br>  
<h1>Data Lapangan</h1>

<table class="table table-bordered">
  <thead>
    <tr>
      <td>No</td>
      <td>Nama Lapangan</td>
      <td>Deskripsi</td>
      <td>Action</td>
    </tr>
  </thead>
    <tbody>

      @foreach($court as $c)
      <tr>
        <td>{{$loop->iteration}}</td>
        <td>{{$c->court_name}}</td>
        <td>{{$c->description}}</td>
        <td>
          <a href="/admin/court/{{$c->id}}" type="submit" class="btn btn-warning">Edit</a>
          <form action="{{ route('court.destroy', $c->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <input onclick="return confirm('Are you sure you want delete {{$c->court_name}} ?')" type="submit" class="btn btn-danger" value="DELETE">
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
</table>


</div>

@endsection