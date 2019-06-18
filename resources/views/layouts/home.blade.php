<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- ADD MY STYLE --}}
     {{-- <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    {{-- ADD MY JS --}}
    {{-- <script src="{{ mix('js/app.js') }}" charset="utf-8"></script> --}}
    
    <title>BoolBnB</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">


  </head>
  <body>
    <header>

      <h1>Header</h1>
        <h2> INSERIRE RICERCA</h2>
    
    
    </header>


    {{-- ERROR CONTROL --}}
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach($errors->all() as $error)
            <li>{{$error}}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @if(session('success'))
      <div class="alert alert-success">
        <div class="container">

          {{session ('success')}}

        </div>
      </div>
    @endif

    {{-- END ERROR CONTROL --}}


    
    @yield('content')




    <footer>
    <h5>It's like Airbnb but made with much more love</h5>
    </footer>




  </body>
</html>
