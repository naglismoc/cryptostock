@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
             <div class="card-header">PAVADINIMAS</div>
             <div class="col-md-12">
 
 
         </div>
            </div>
            <div class="card">
                
 
                <div class="card-body">




<table class="table">
    <tr>
        <th>Name</th>
        <th>Nickname</th>
        <th>trade</th>
        <th>redaguoti</th>
        <th>trinti</th>
    </tr>
  

@foreach ($stocks as $stock)
<tr>
    <td><h2>{{$stock->name}}</h2></td>
    <td><h2>{{$stock->nickname}}</h2></td>
    <td><a class="btn btn-success" href="{{route('stock.show',$stock)}}">prekiauti</a></td>
    <td><a class="btn btn-primary" href="{{route('stock.edit',$stock)}}">redaguoti</a></td>
    <td>
        <form action="{{route('stock.destroy',$stock)}}" method="post">
            @csrf
            <input  class="btn btn-danger" type="submit" value="trinti">
        </form>
    </td>
  </tr>
@endforeach

</div>
</div>
</div>
</div>
</div>

@endsection