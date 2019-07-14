@extends('layouts.home')

@section('content-header')

  @include('layouts.header')

  <div id="advanced-search-component-wrapper">
    <advanced-search :services={{$services}} address="{{$address}}" rooms="@php
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

  <h3 class="ml-5">Appartamenti in evidenza:</h3>

    <div id="apartment-component-wrapper" class="d-flex flex-wrap justify-content-center">
      @foreach ($sponsoredApartments as $sponsoredApartment)
        <div class="apartment col-lg-4">
          <apartment-component v-if="{{count($sponsoredApartments)}}>0" :apartment="{{$sponsoredApartment}}"></apartment-component>
        </div>
        <h1 v-else>Non ci sono appartamenti sponsorizzati!</h1>
      @endforeach
    </div>

  <div class="result-box ml-5">
    <h3>Risultati:</h3>

    <div id="apartments-component-wrapper">
      <apartments-component v-bind:apartments-prop="{{$queryApartments}}"></apartments-component>
    </div>
  </div>
@stop
