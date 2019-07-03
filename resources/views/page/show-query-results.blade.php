@extends('layouts.home')

@section('content-header')

  @include('layouts.header')

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
  @include ('components.apartment-component')


  {{-- <h3 class="ml-5">Appartamenti in evidenza:</h3> --}}

  <div class="sponsored-container">

    <div id="sponsored-component-wrapper" class="d-flex flex-wrap justify-content-center">

      @if (count($sponsoredApartments)==0)
        <h1>Non ci sono appartamenti sponsorizzati</h1>
      @endif
       <div class="sponsor-title">
         <small>Sponsored</small>
       </div>
      @foreach ($sponsoredApartments as $key => $sponsoredApartment)
        <apartment-component description="{{$sponsoredApartment->title}}" image={{$sponsoredApartment->image}} alt-image="{{asset('images/' . $sponsoredApartment->image)}}" address="{{$sponsoredApartment->address}}" v-bind:visuals="{{$sponsoredApartment->visuals->count()}}" show-index="{{route('show',$sponsoredApartment->id)}}"></apartment-component>
      @endforeach
    </div>
  </div>
   <div class="result-box">


     <h5>Risultati:</h5>


      <div id="apartment-component-wrapper" class="d-flex flex-wrap justify-content-center">

        @if ($queryApartments->count()==0)
          <h1>Non ci sono risultati!</h1>
        @endif

        @foreach ($queryApartments as $apartment)
          <apartment-component description="{{$apartment->title}}" image={{$apartment->image}} alt-image="{{asset('images/' . $apartment->image)}}" address="{{$apartment->address}}" v-bind:visuals="{{$apartment->visuals->count()}}" show-index="{{route('show',$apartment->id)}}"></apartment-component>
        @endforeach
      </div>
   </div>
@stop
