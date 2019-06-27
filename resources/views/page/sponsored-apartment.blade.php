@extends('layouts.home')
@section('content-header')

  {{-- Ricerca --}}

  <form action="{{route('basic-search')}}" method="get">
    <div class="form-group">
      <input type="hidden" name="lat">
      <input type="hidden" name="lon">
      <input type="hidden" name="advancedSearch" value="0">
      <input class="address-search" type="text" name="address" value="" placeholder="Insert address...">
    </div>

    <div class="query-results"></div>

    <input id="search-btn" type="submit" name="" value="SEARCH">
  </form>
@stop

@section('content')
  @include ('components.apartment-component')

  <div class="container-fluid mt-4">
    <div id="sponsoreds-wrapper" class="d-flex flex-wrap">
      @foreach ($sponsoreds as $sponsored)
        <apartment-component description="{{$sponsored->title}}" image={{$sponsored->image}} address="{{$sponsored->address}}" v-bind:visual="{{$sponsored->visuals->count()}}" show-index="{{route('show',$sponsored->id)}}"></apartment-component>
      @endforeach
    </div>
  </div>

@stop
