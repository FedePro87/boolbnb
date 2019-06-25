@extends('layouts.home')
@section('content')

<h1>Qui andrà la sezione degli appartamenti sponsorizzati</h1>
{{-- @foreach ($sponsoreds as $sponsored)
  <div>
      <img src="{{$sponsored->image}}" alt="" style="width:100px">
      <h4>{{$sponsored->title}}</h4>
      <p>{{$sponsored->description}}</p>
  </div>
@endforeach --}}

<h1>Risultati ricerca:</h1>

@foreach ($queryApartments as $apartment)
  <div>
      <img src="{{asset('images/' . $apartment->image)}}" alt="" style="width:100px">
      <h4>{{$apartment->title}}</h4>
      <p>{{$apartment->description}}</p>
  </div>
@endforeach

@stop
