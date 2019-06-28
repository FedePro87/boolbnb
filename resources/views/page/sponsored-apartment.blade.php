@extends('layouts.home')

{{-- Ricerca --}}
@section('content-header')
  <div class="main-header">
    <div class="form-style">
      @guest
        <h1>Ciao, dove vuoi andare?</h1>

      @endguest
      @if(Auth::user()!==null)
        <h1>Ciao {{ Auth::user()->firstname }}, dove vorresti andare?</h1>

      @endif
      <form action="{{route('basic-search')}}" method="get">
        <div class="form-group">
          <input type="hidden" name="lat">
          <input type="hidden" name="lon">
          <input type="hidden" name="advancedSearch" value="0">
          <input class="address-search" type="text" name="address" value="" placeholder="Insert address...">
        </div>

        <div class="query-results"></div>

        <input class="boolbnb-btn" type="submit" name="" value="SEARCH">
      </form>
    </div>
  </div>
@stop

@section('content')
  @include ('components.apartment-component')

  <div class="container-fluid mt-5">
    <div id="apartment-component-wrapper" class="d-flex flex-wrap justify-content-center">
      @foreach ($sponsoreds as $sponsored)
        <apartment-component description="{{$sponsored->title}}" image={{$sponsored->image}} alt-image="{{asset('images/' . $sponsored->image)}}" address="{{$sponsored->address}}" v-bind:visuals="{{$sponsored->visuals->count()}}" show-index="{{route('show',$sponsored->id)}}"></apartment-component>
      @endforeach
    </div>
  </div>

@stop
