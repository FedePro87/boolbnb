@extends('layouts.home')

{{-- Ricerca --}}
@section('content-header')
  <div class="main-header">
    <div class="navbar-wrapper">
      @include('layouts.header')
    </div>
    <div class="bg-form-wrapper">


      <div class="form-style col-lg-4 col-md-9">
        @guest
          <h1>Ciao, dove vuoi andare?</h1>
        @endguest
        @if(Auth::user()!==null)
          <h1>Ciao {{ Auth::user()->firstname }}, dove vorresti andare?</h1>
        @endif

        <form action="{{route('apartment-search')}}" method="get">
          <div class="form-group">
            <div id="address-search-component-wrapper">
              <address-search-component :home-search=true></address-search-component>
            </div>
          </div>
          <input class="boolbnb-btn" type="submit" name="" value="SEARCH">
        </form>
      </div>
    </div>

  </div>
@stop

@section('content')

  <div class="container-fluid mt-5">
    <div id="apartments-component-wrapper" v-if="{{count($sponsoreds)}}>0" class="d-flex flex-wrap justify-content-center">
      @foreach ($sponsoreds as $sponsored)
        <div class="apartment col-lg-4">
          <apartment-component :apartment="{{$sponsored}}"></apartment-component>
        </div>
      @endforeach
      <h1 v-if="{{count($sponsoreds)}}===0">Non ci sono appartamenti sponsorizzati!</h1>
    </div>
  </div>

@stop
