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

  @foreach ($sponsoreds as $sponsored)
    <div>
      <img src="{{$sponsored->image}}" alt="" style="width:100px">
      <h4>{{$sponsored->title}}</h4>
      <p>{{$sponsored->description}}</p>
    </div>
  @endforeach

@stop
