@extends('layouts.home')
@section('content')

  <h1>User Apartments</h1>

  @include ('components.apartment-component')

  <div class="container-fluid mt-4">
    <div id="apartment-component-wrapper" class="d-flex flex-wrap">
      @foreach ($user->apartments as $apartment)
        <apartment-component description="{{$apartment->title}}" image={{$apartment->image}} alt-image="{{asset('images/' . $apartment->image)}}" address="{{$apartment->address}}" v-bind:visuals="{{$apartment->visuals->count()}}" v-bind:messages="{{$apartment->messages->count()}}" show-index="{{route('show',$apartment->id)}}"></apartment-component>
      @endforeach
    </div>
  </div>

@stop
