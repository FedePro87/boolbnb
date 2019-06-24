@extends('layouts.home')
@section('content')
{{--
@foreach ($sponsorships as $sponsorship)
  <div>
      <h4>{{$sponsorship->id}}</h4>
      <p>{{$sponsorship->type}}</p>
  </div>
@endforeach --}}

<div class="add-sponsorship">

  <form class="" action="{{route('updateSponsorship', $apartment->id)}}" method="post">
    @csrf
    

    @foreach ($sponsorships as $sponsorship)
      <input type="radio" name="sponsorships" value="{{$sponsorship->id}}">        {{$sponsorship->type}}</input><br>


    @endforeach

      <button type="submit" name="button">Add Sponsorship</button>
  </form>

</div>

@stop
