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
  <div data-lat={{$apartment->lat}} data-lng={{$apartment->lng}} id='map'></div>

  {{-- END MAP SECTION --}}



  {{-- Contact form --}}

  <h3>Scrivi al proprietario</h3>

  {{-- <form action="">

    <label for="">Oggetto</label><br>
    <input type="text"><br>

    <label for="">Testo</label><br>
    <textarea name="" id="" cols="30" rows="10"></textarea><br>

    <button type="submit" name="button">Invia</button>

  </form> --}}

  <form class="create" action="{{route('create-message',$apartment->id)}}" method="post">
  @csrf

  @guest
  <label for="email">indirizzo mail</label><br>
  <input type="text" name="email" value=""><br>   
  @endguest

  {{-- @auth
  <input type="text" name="email" value="{{Auth::user()->email}}"><br>
  @endauth --}}

  <label for="title">Oggetto</label><br>
  <input type="text" name="title" value="Oggetto della mail"><br>

  <label for="content">Testo della Mail</label><br>
  <textarea name="content" rows="10" cols="30"></textarea><br>

  <button type="submit" name="button">Send Mail</button>
  </form>

  {{-- END Contact form --}}

  @if(Auth::user()!==null)
    @if($apartment->user_id==Auth::user()->id)
    <div>
      <h1>Visualizzazioni totali: {{$apartment->visuals->count()}}</h1>
      <h1>Messaggi totali: {{$apartment->messages->count()}}</h1>
      <canvas id="visualsChart" data-stats={{$visualsData}} data-months={{$months}}></canvas>
     <canvas id="messagesChart" data-stats={{$messagesData}} data-months={{$months}}></canvas>
    </div>
    @endif
  @endif

@stop
