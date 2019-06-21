@extends('layouts.home')
@section('content')

@foreach ($sponsoreds as $sponsored)
  @foreach ($sponsored->apartments as $apartment)

  <div>
      <img src="{{$apartment->image}}" alt="" style="width:100px">
      <h4>{{$apartment->title}}</h4>
      <p>{{$apartment->description}}</p>
  </div>
  @endforeach
@endforeach


@stop