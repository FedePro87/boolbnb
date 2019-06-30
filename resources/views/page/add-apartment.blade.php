@extends('layouts.home')
@section('content')

  @auth
    <h1 class="text-center">Inserisci il nuovo appartamento</h1>
    <form enctype="multipart/form-data" class="" action="{{route('save')}}" method="post">
      @csrf


      <div class="container title-container d-flex align-items-center justify-content-between">
        <label for="title">Title</label>

        <input class="w-75"type="text" name="title" value="">

        <label id="fake-upload-image" for="image"><h5>Add Image</h5></label>
        <input type="file" name="image" id="upload-image">
      </div>

      <br>
      <div class="container my-container">
        <div class="description-price-container col-12 justify-content-center">

          <div class="col-lg-8 col-sm-12 col-12">
            <label for="description">Description</label>
            <br>
            <textarea name="description" class="col-lg-12 col-md-6">{{ old('description')}}</textarea>
          </div>

          <div class="col-lg-4 col-sm-12 col-12 align-items-center flex-column">
            <label for="price">Price</label>
            <br>
            <input type="text" name="price" value="{{ old('price')}}">
          </div>
        </div>
        <br>

        {{-- ROOMS --}}
        <div class="container-select d-flex justify-content-between">
          <div class="rooms">
            <label for="number_of_rooms">Rooms</label><br>
            <select name="number_of_rooms">

              @for ($i=1; $i<=10; $i++)
                {
                  <option value="{{$i}}">{{$i}}</option>
                }
              @endfor

            </select>
          </div>
          <br>

        {{-- BATHROOMS --}}
          <div class="bathrooms">
            <label for="bathrooms">Bathrooms</label><br>
            <select name="bathrooms">

              @for ($i=1; $i<=10; $i++)
                {
                  <option value="{{$i}}">{{$i}}</option>
                }
              @endfor

            </select>
          </div>
          <br>

        {{-- BEDROOMS --}}
          <div class="bedrooms">
            <label for="bedrooms">Bedrooms</label><br>
            <select name="bedrooms">
              @for ($i=1; $i<=10; $i++)
                {
                  <option value="{{$i}}">{{$i}}</option>
                }
              @endfor
            </select>
          </div>
        </div>


        <br>
        <div class="square-address-container d-flex align-items-center">

          <label for="square_meters">Square Meters</label>
          <br>
          <input  class="mx-2" type="text" name="square_meters" value="{{ old('square_meters')}}">

          <label for="address">Address</label>
          <input class="mx-2 address-search" type="text" name="address">
          <div class="query-results position-absolute bg-light"></div>

        </div>
        <br>

        <div class="checkbox-cont">
          <label for="services">services</label>
          <br>
          @foreach ($services as $service)
            <input type="checkbox" name="services[]" value="{{$service->id}}">{{$service->name}}
            <br>
          @endforeach
        </div>
        <br>

        <button id="save-apartment" type="submit" name="button">Save New Apartment</button>

      </div>
    </form>
  @endauth
  @guest
    <h1>Devi essere loggato per aggiungere un appartamento!</h1>
  @endguest

@endsection
