@extends('layouts.home')
@section('content')
  @include('layouts.header')

  @auth
    <form enctype="multipart/form-data" class="mt-4" action="{{route('save')}}" method="post">
      @csrf

      <div class="row">

        <div class="col-lg-4">
          <label for="title"><h2>Title</h2></label>
          <input type="text" name="title" value="{{ old('title')}}">
        </div>

        <div class="col-lg-4">
          <label for="price"><h2>Price</h2></label>
          <input type="text" name="price" value="{{ old('price')}}">
        </div>

        <div class="col-lg-4">
          <label for="square_meters"><h2>Square Meters</h2></label>
          <input type="text" name="square_meters" value="{{ old('square_meters')}}">
        </div>
      </div>

      <div class="row">
        <div class="col-lg-6 mt-4">
          <label for="description"><h2>Description</h2></label>
          <br>
          <textarea name="description" rows="8" cols="80">{{ old('description')}}</textarea>
        </div>

        <div class="col-lg-6 d-flex align-items-center">
          <div class="add-image-wrapper">
            <label id="fake-upload-image" for="image" class="mr-4"><h2>Add Image</h2></label>
            <input type="file" name="image" id="upload-image">
          </div>
        </div>
      </div>

      <div class="row">

        <div class="col-lg-2">
          <label for="number_of_rooms"><h2>Rooms</h2></label>
          <select name="number_of_rooms">

            @for ($i=1; $i<=10; $i++)
              {
                <option value="{{$i}}">{{$i}}</option>
              }
            @endfor
          </select>
        </div>

        <div class="col-lg-2">
          <label for="bathrooms"><h2>Bathrooms</h2></label>
          <select name="bathrooms">
            @for ($i=1; $i<=10; $i++)
              {
                <option value="{{$i}}">{{$i}}</option>
              }
            @endfor
          </select>
        </div>

        <div class="col-lg-2">
          <label for="bedrooms"><h2>Bedrooms</h2></label>
          <select name="bedrooms">
            @for ($i=1; $i<=10; $i++)
              {
                <option value="{{$i}}">{{$i}}</option>
              }
            @endfor
          </select>
        </div>
      </div>

      <div class="address-search-wrapper col-lg-6">
        <label for="address"><h2>Address</h2></label>
        <div class="close-results-wrapper">
          <input class="address-search" type="text" name="address"><i class="fas fa-times d-none"></i>
        </div>
        <div class="query-results"></div>
        <br>
      </div>

      <div class="checkbox-cont">
        <label for="services"><h2>services</h2></label>
        <br>
        @foreach ($services as $service)
          <input type="checkbox" name="services[]" value="{{$service->id}}">{{$service->name}}
          <br>
        @endforeach
      </div>
      <br>

      <button id="save-apartment" type="submit" name="button">Save New Apartment</button>

    </form>
  @endauth
  @guest
    <h1>Devi essere loggato per aggiungere un appartamento!</h1>
  @endguest

@endsection
