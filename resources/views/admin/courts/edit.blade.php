@extends('layouts.app')
@section('content')

<div class="m-5">
<a href="/admin" class="btn btn-primary">HOME</a> <br><br>

@if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif

  <form action="/admin/court/{{$court->id}}" method="post">
    @csrf
    @method('PUT')
    <h1>Edit Lapangan</h1>
    <div class="mb-3">
      <label for="court_name" class="form-label">Nama Lapangan</label>
      <input type="text" class="form-control" id="court_name" name="court_name" value="{{$court->court_name}}">
    </div>
    <div class="mb-3">
      <label for="exampleFormControlTextarea1" class="form-label">Deskripsi</label>
      <textarea class="form-control" id="description" rows="3" name="description" value="{{$court->description}}"></textarea>
    </div> 
  <button type="submit" class="btn btn-success">Update</button>
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

      @foreach($allCourt as $court)
      <tr>
        <td>{{$loop->iteration}}</td>
        <td>{{$court->court_name}}</td>
        <td>{{$court->description}}</td>
        <td>
            <a href="/admin/court/{{$court->id}}" type="submit" class="btn btn-warning">Edit</a>
            <form action="{{ route('court.destroy', $court->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <input onclick="return confirm('Are you sure you want delete {{$court->court_name}} ?')" type="submit" class="btn btn-danger" value="DELETE">
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
</table>


</div>

@endsection