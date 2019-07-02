@extends('layouts.home')
@section('content')
  @include('layouts.header')


  <div class="container my-container">
  @auth
    <h1 class="text-center">Inserisci il nuovo appartamento</h1>
    <form enctype="multipart/form-data" class="" action="{{route('save')}}" method="post">
      @csrf


      <div class="container title-container align-items-center justify-content-between">
        <label for="title">Title</label><br>
        <input class="w-75"type="text" name="title" value=""><br>
        <label class="mt-3" >Add image</label><br>
        <input type="file" name="image" id="upload-image">
      </div>

      <br>
        <div class="description-price-container p-0 col-12">

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
        <div class="container-select d-flex">
          <div class="rooms mr-5 d-flex flex-column align-item-center">
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
          <div class="bathrooms mr-5 d-flex flex-column align-item-center">
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
          <div class="bedrooms mr-5 d-flex flex-column align-item-center">
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
