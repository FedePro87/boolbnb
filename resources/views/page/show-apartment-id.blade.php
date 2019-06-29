@extends('layouts.home')
@section('content')

  {{-- Show apartment info --}}
  @include('layouts.header')
  <div class="show-apartment mt-4">
    <div class="container">
      <div class="col-lg-6">
        <div class="image-title-wrapper">
          <img class="img-fluid"
          @if(file_exists(public_path('images/' . $apartment->image)))
            src="{{asset('images/' . $apartment->image)}}"
          @else
            src="{{$apartment->image}}"
          @endif
          >
          <h4 class="image-title">{{$apartment->title}}</h4>
        </div>
      </div>
    </div>
    <div class="container mt-4">
      <div class="row">
        <div class="col-lg-6">
          <h5>Costo per notte: {{$apartment->price}}€</h5>
          <p>{{$apartment->description}}</p>
        </div>

        <div class="col-lg-6">
          <ol class="text-center list-unstyled">
            <li>Numero di stanze: {{$apartment->number_of_rooms}}</li>
            <li>Numero di posti letto: {{$apartment->bedrooms}}</li>
            <li>Numero di bagni: {{$apartment->bathrooms}}</li>
            <li>Metri quadrati: {{$apartment->square_meters}}</li>
            <li>Indirizzo: {{$apartment->address}}</li>
          </ol>

          <ul class="text-center list-unstyled">
            @foreach ($apartment->services as $service)
              <li>{{$service->name}}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
    {{-- END Show apartment info --}}

    {{-- MAP SECTION --}}
    <div class="container mt-4">
      <div class="row">
        <div class="col-lg-6" data-lat={{$apartment->lat}} data-lng={{$apartment->lng}} id='map'></div>
        {{-- END MAP SECTION --}}

        {{-- Contact form --}}
        @if(Auth::user()!==null)
          @if($apartment->user_id!=Auth::user()->id)
            <form class="create col-lg-6" action="{{route('create-message',$apartment->id)}}" method="post">
              <div class="d-flex justify-content-center align-items-center">
                <div class="message-form-wrapper">
                  @csrf
                  <h3>Scrivi al proprietario</h3><br>

                  @guest
                    <label for="email">indirizzo mail</label><br>
                    <input type="text" name="email" value=""><br>
                  @endguest

                  <label for="title">Oggetto</label><br>
                  <input type="text" name="title" value="Oggetto della mail"><br>

                  <label for="content">Testo della Mail</label><br>
                  <textarea name="content" rows="10" cols="30"></textarea><br>

                  <button type="submit" name="button">Send Mail</button>
                </div>
              </div>
            </form>
          @else
            <div class="col-lg-6 overflow-auto messages">
              @if ($apartment->messages->count()==0)
                <h1>Non hai ricevuto messaggi per questo appartamento!</h1>
              @else
                @foreach ($apartment->messages as $message)
                  <div class="border">
                    <h5>Titolo messaggio: {{$message->title}}</h5>
                    <h5>Contenuto: {{$message->content}}</h5>
                    <h5>Inviato da: <a href="" data-mail={{$message->email}} data-title={{$apartment->title}} class="emailLink">{{$message->email}}</a></h5>
                  </div>
                @endforeach
              @endif
            </div>
          @endif
        @endif
      </div>
    </div>

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
  </div>

@stop
