@extends('layouts.home')
@section('content')

@foreach ($sponsoreds as $sponsored)
  <div>
      <img src="{{$sponsored->image}}" alt="" style="width:100px">
      <h4>{{$sponsored->title}}</h4>
      <p>{{$sponsored->description}}</p>
  </div>
@endforeach

@stop
