@extends('layouts.home')
@section('content')

  {{-- Show apartment info --}}

  <h1>{{$apartment->title}}</h1>
  <h5>Costo per notte: {{$apartment->price}}€</h5>

  <img src="{{asset('images/' . $apartment->image)}}" alt="">

  <p>{{$apartment->description}}</p>


  <ol>
    <li>Numero di stanze: {{$apartment->number_of_rooms}}</li>
    <li>Numero di posti letto: {{$apartment->bedrooms}}</li>
    <li>Numero di bagni: {{$apartment->bathrooms}}</li>
    <li>Metri quadrati: {{$apartment->square_meters}}</li>
    <li>Indirizzo: {{$apartment->address}}</li>
  </ol>


  <ul>
    @foreach ($apartment->services as $service)
      <li>{{$service->name}}</li>
    @endforeach
  </ul>



  {{-- END Show apartment info --}}



  {{-- MAP SECTION --}}

  <div id='map'></div>

  {{-- END MAP SECTION --}}



  {{-- Contact form --}}

  <h3>Scrivi al proprietario</h3>

  <form action="">

    <label for="">Oggetto</label><br>
    <input type="text"><br>

    <label for="">Testo</label><br>
    <textarea name="" id="" cols="30" rows="10"></textarea><br>

    <button type="submit" name="button">Invia</button>

  </form>

  {{-- END Contact form --}}


  {{--
  @if($logged)
  <div>
  visualizza statistiche
</div>
@endif --}}



@stop
