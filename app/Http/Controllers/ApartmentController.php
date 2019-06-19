<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Apartment;
use App\Sponsorship;
use App\Service;

class ApartmentController extends Controller
{
 public function show($id)
  {
    $apartment = Apartment::findOrFail($id);
        //   dd($apartment);
          return view('page.show-apartment-id', compact('apartment'));
  }



  public function showSponsored(){
    $sponsoreds = Sponsorship::all();
      return view('page.sponsored-apartment', compact('sponsoreds'));


  }

  public function search(Request $request){

   $title = $request -> title;
   $description = $request -> description;
   $service = $request-> service;
   $query = Apartment::query();

   if ($title) {
      $query = $query ->where('title', 'LIKE', '%' . $title . '%');
    }
    if ($description) {
      $query = $query ->where('description', 'LIKE', '%' . $description . '%');
    }


    $apartments = $query ->get();

    $services = Service::all();

    return view('page.search', compact( 'apartments','services'));

  }
}
