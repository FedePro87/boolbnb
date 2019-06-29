@extends('layouts.home')

@section('content-header')
  @include ('components.advanced-search')

  <div id="component-vue">
    <advanced-search address="{{$address}}" rooms="@php
    if (isset($numberOfRooms)){
      echo $numberOfRooms;
    } else {
      echo 0;
    }
    @endphp" bedrooms="@php
    if (isset($bedrooms)){
      echo $bedrooms;
    } else {
      echo 0;
    }
    @endphp" radius="{{$maxDistance}}" lat={{$lat}} lon={{$lon}}></advanced-search>
  </div>

@endsection

@section('content')
  <h1>Risultati ricerca:</h1>


  <div id="query-apartments">

    @if ($queryApartments->count()==0)
      <h1>Non ci sono risultati!</h1>
    @endif

    @foreach ($queryApartments as $apartment)
      <div>
        <img src="{{asset('images/' . $apartment->image)}}" alt="" style="width:100px">
        <h4>{{$apartment->title}}</h4>
        <p>{{$apartment->description}}</p>
      </div>
    @endforeach
  </div>

@stop
