@extends('layouts.app')

@section('content')
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Akcijos įtraukimas į biržą</div>

                <div class="card-body">
                
        <form action="{{route('stock.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            pavadinimas
            <input type="text" name="name" value="{{old('name')}}"> <br>
            trumpinys 
             <input type="text" name="nickname" value="{{old('nickname')}}"> <br>
            
        
            {{-- <label><b>Įkelkite <span style="color: red;"> nuotraukas </span> čia</b></label>
            <input type="file" id="fileToUpload" name="photo" style="display: none">
            <div onclick="document.getElementById('fileToUpload').click()" class="btn btn-primary" style="padding: 0.075rem 0.5rem;">Spausk čia</div><br>
            <small id="fileText" class="form-text text-muted"></small> --}}

           
            <input class="btn btn-primary" type="submit" value="Išsaugoti">
        </form>

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

