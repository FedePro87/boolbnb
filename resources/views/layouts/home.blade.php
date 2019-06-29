<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- ADD MY STYLE --}}
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  {{-- ADD MY JS --}}
  <script src="{{ mix('js/app.js') }}" charset="utf-8"></script>

  <title>BoolBnB</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
  <!-- Handlebars -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.1.0/handlebars.min.js" charset="utf-8"></script>

  <link rel="stylesheet" href="{{ asset('tomtom-sdk/map.css') }}">
  <script src="{{ asset('tomtom-sdk/tomtom.min.js')}}" type="text/javascript"></script>
  <script src="{{ asset('dropin.min.js')}}" type="text/javascript"></script>

  <script id="apartment-template" type="text/x-handlebars-template">
    <div class="apartment">
      <img src="/images/@{{image}}" alt="" style="width:100px">
      <h4>@{{title}}</h4>
      <p>@{{description}}</p>
    </div>
  </script>
</head>
<body>

  @include('layouts.header')


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

  @yield('content-header')


  @yield('content')




  <footer>
    <h5>It's like Airbnb but made with much more love</h5>
  </footer>



</body>
</html>
