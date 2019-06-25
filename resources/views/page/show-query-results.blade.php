@extends('layouts.home')

@section('content-header')
  <h1>Ricerca Avanzata</h1>

  <form action="{{route('basic-search')}}" method="get">
    <div class="form-group">
      <input type="hidden" name="lat">
      <input type="hidden" name="lon">
      <input class="address-search" type="text" name="address" value="" placeholder="Insert address...">
    </div>

    <div class="query-results"></div>

    <div class="form-group d-flex">
      <label for="service">Services</label><br>

        @foreach ($services as $service)
             <input type="checkbox" name="services[]" value="{{$service->id}}"><label>{{$service->name}}  </label><br>
        @endforeach

    </div>


    <input id="search-btn" type="submit" name="" value="SEARCH">
  </form>
@endsection

@section('content')
  <h1>Risultati ricerca:</h1>

  @foreach ($queryApartments as $apartment)
    <div>
      <img src="{{asset('images/' . $apartment->image)}}" alt="" style="width:100px">
      <h4>{{$apartment->title}}</h4>
      <p>{{$apartment->description}}</p>
    </div>
  @endforeach

@stop
