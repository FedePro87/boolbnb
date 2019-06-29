@extends('layouts.home')
@section('content')

  @auth
    <form enctype="multipart/form-data" class="" action="{{route('save')}}" method="post">
      @csrf

      <div class="container title-container d-flex">
        <label for="title"><h2>Title</h2></label>
        <br>
        <input class="w-75"type="text" name="title" value="">
        <br>

        <label id="fake-upload-image" for="image"><h2>Add Image</h2></label>
        <br>
        <input type="file" name="image" id="upload-image">
      </div>
      <br>

      <label for="description"><h2>Description</h2></label>
      <br>
      <textarea name="description" rows="8" cols="80"></textarea>
      <br>

      <label for="price"><h2>Price</h2></label>
      <input type="text" name="price">
      <br>

      <label for="number_of_rooms"><h2>Rooms</h2></label>

      <select name="number_of_rooms">

        @for ($i=1; $i<=10; $i++)
          {
            <option value="{{$i}}">{{$i}}</option>
          }
        @endfor
      </select><br>

      <label for="bathrooms"><h2>Bathrooms</h2></label>
      <select name="bathrooms">
        @for ($i=1; $i<=10; $i++)
          {
            <option value="{{$i}}">{{$i}}</option>
          }
        @endfor
      </select><br>

      <label for="bedrooms"><h2>Bedrooms</h2></label>
      <select name="bedrooms">
        @for ($i=1; $i<=10; $i++)
          {
            <option value="{{$i}}">{{$i}}</option>
          }
        @endfor
      </select><br>

      <label for="square_meters"><h2>Square Meters</h2></label>
      <input type="text" name="square_meters">
      <br>

      <label for="address"><h2>Address</h2></label>
      <input class="address-search" type="text" name="address">
      <div class="query-results"></div>
      <br>

      <div class="checkbox-cont">
        <label for="services"><h2>services</h2></label>
        <br>
        @foreach ($services as $service)
          <input type="checkbox" name="services[]" value="{{$service->id}}">{{$service->name}}
          <br>
        @endforeach
      </div>
      <br>

      <button type="submit" name="button">Save New Apartment</button>

    </form>
  @endauth
  @guest
    <h1>Devi essere loggato per aggiungere un appartamento!</h1>
  @endguest

@endsection
