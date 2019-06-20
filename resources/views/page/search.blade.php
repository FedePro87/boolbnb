@extends('layouts.home')
@section('content')

<div class="search-box">

  <form action="{{route('search')}}" method="get">
    <div class="form-group">
      <label for="title">Title</label><br>
      <input type="text" name="title" value="">
    </div>

    <div class="form-group">
      <label for="service">Services</label><br>

        @foreach ($services as $service)
             <input type="checkbox" name="services[]" value="{{$service->id}}"><label>{{$service->name}}  </label><br>
        @endforeach

    </div>


    <input id="search-btn" type="submit" name="" value="SEARCH">



  </form>
</div>


<div class="show">
    @foreach ($apartments as $apartment)

    <div class="apartment-box">
      <img src="{{$apartment->image}}" alt="">
      <div class="title-box">

      <h3>{{$apartment->title }} </h3>
    </div>
    <div class="content-box">

     <p>{{$apartment->description}}</p>

      @foreach ($apartment->services as $service)
        <small> {{$service->name}} - </small>
       @endforeach

     </div>
    </div>
  @endforeach

  @if (!isset($apartments[0]))
    
    <h1>Non ci sono risultati!</h1>
  @endif
</div>


@stop
