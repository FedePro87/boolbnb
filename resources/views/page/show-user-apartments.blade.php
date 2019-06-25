@extends('layouts.home')
@section('content')

<h1>User Apartments</h1>

@foreach ($user->apartments as $apartment)

  <p>{{$apartment->title}}</p>
  <p>{{$apartment->id}}</p>
  <h1> Conteggio:{{$apartment->messages->count()}}</h1>
@endforeach

@stop
