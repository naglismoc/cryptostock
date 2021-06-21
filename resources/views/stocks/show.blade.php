<script>
    const getStock = "{{route('stock.data',$stock)}}";
  </script>
   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
@extends('layouts.app')

@section('content')
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Akcijos įtraukimas į biržą</div>

                <div class="card-body">
                
        <form action="{{route('order.store',$stock)}}" method="post">
            @csrf
            Kiekis
            <input type="text" name="quantity" value="{{old('quantity')}}"> <br>
            kaina
            <input type="text" name="price" value="{{old('price')}}"> <br>
            
            <input class="btn btn-primary" name="sell" type="submit" value="Parduoti">
            <input class="btn btn-primary" name="buy" type="submit" value="Pirkti">
            
        
            {{-- <label><b>Įkelkite <span style="color: red;"> nuotraukas </span> čia</b></label>
            <input type="file" id="fileToUpload" name="photo" style="display: none">
            <div onclick="document.getElementById('fileToUpload').click()" class="btn btn-primary" style="padding: 0.075rem 0.5rem;">Spausk čia</div><br>
            <small id="fileText" class="form-text text-muted"></small> --}}

           
          
        </form>
        <div id="chart_div" style="width: 100%; height: 500px;"></div>
       

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

