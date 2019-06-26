@extends('layouts.home')

@section('content-header')
  <h1>Ricerca Avanzata</h1>

  <form class="form-group" action="{{route('basic-search')}}" method="get">
    <div class="d-flex">
      <div>
        <div class="form-group">
          <input type="hidden" name="lat">
          <input type="hidden" name="lon">
          <input class="address-search" type="text" name="address" value="{{$address}}" placeholder="Insert address...">
        </div>
        <div class="query-results"></div>
      </div>

      <div>
        <label for="number_of_rooms"><h2>Rooms</h2></label>
        <select name="number_of_rooms">

          @for ($i=0; $i<=10; $i++)
            @php
            if ($i==0) {
              $i='*';
            }
            @endphp
            <option value="{{$i}}"
            @if ($numberOfRooms==$i)
              selected
            @endif
            >{{$i}}</option>
            @php
            if ($i=='*') {
              $i=0;
            }
            @endphp
          @endfor
        </select><br>
      </div>

      <div>
        <label for="bedrooms"><h2>Bedrooms</h2></label>
        <select name="bedrooms">
          @for ($i=0; $i<=10; $i++)
            @php
            if ($i==0) {
              $i='*';
            }
            @endphp
            <option value="{{$i}}"
            @if ($bedrooms==$i)
              selected
            @endif
            >{{$i}}</option>
            @php
            if ($i=='*') {
              $i=0;
            }
            @endphp
          @endfor
        </select><br>
      </div>

      <div class="ml-4">
        <label for="radius"><h2>Distanza</h2></label>
        <select name="radius">
          @for ($i=1; $i<=5; $i++)
            <option value="{{$i*20}}"
            @if ($maxDistance==$i*20)
              selected
            @endif
            >{{$i*20}} km</option>
          @endfor
        </select><br>
      </div>
    </div>

    <div class="d-flex">
      <label for="service">Services</label><br>

      @foreach ($services as $service)
        <input type="checkbox" name="services[]" value="{{$service->id}}"
        @isset($queryServices)
          @foreach ($queryServices as $queryService)
            @if ($queryService==$service->id)
              checked
            @endif
          @endforeach
        @endisset
        ><label>{{$service->name}}</label><br>
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
