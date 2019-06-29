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

  @include ('components.apartment-component')

  <div id="apartment-component-wrapper" class="d-flex">

    @if ($queryApartments->count()==0)
      <h1>Non ci sono risultati!</h1>
    @endif

    @foreach ($queryApartments as $apartment)
      <apartment-component description="{{$apartment->title}}" image={{$apartment->image}} alt-image="{{asset('images/' . $apartment->image)}}" address="{{$apartment->address}}" v-bind:visuals="{{$apartment->visuals->count()}}" show-index="{{route('show',$apartment->id)}}"></apartment-component>
    @endforeach
  </div>

@stop
