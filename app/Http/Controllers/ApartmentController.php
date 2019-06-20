<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Apartment;
use App\Sponsorship;
use App\Service;
use DB;

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
   $services = $request-> services;
   $data=$request->all();
  $apartments= new Apartment;

  if (isset($data['title'])) {
    $apartments = $apartments ->where('title', 'LIKE', '%' . $title . '%');
  }

  if(isset($data['services'])){
    foreach($data['services'] as $service){
      $apartments = $apartments->whereHas('services', function($q)use($service){
  
        $q->where('service_id', $service); //this refers id field from services table
  
      });
    }
  }
  
  $apartments = $apartments ->get();

  $services = Service::all();
  
  // dd($apartments);

  return view('page.search', compact( 'services','apartments'));
  }
}
