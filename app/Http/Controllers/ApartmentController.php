<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewApartmentRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Apartment;
use App\Sponsorship;
use App\Service;
use App\User;

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
  
  return view('page.search', compact( 'services','apartments'));
  }


  // Creazione nuovo appartamento - tutto questa roba andrà spostata nell'HomeController
  function createNewApartment(){
    
        $apartment = Apartment::all();
        $services = Service::all();
        
        return view('page.add-apartment' , compact('apartment','services'));
    }


    function saveNewApartment(NewApartmentRequest $request){
      $validateData = $request -> validated();

      $apartment = Apartment::make($validateData);
      $inputAuthor= Auth::user()->firstname;
      $user= User::where('firstname','=',$inputAuthor)->first();
      $apartment->user()->associate($user);

      if ($request->hasFile('image')) {
        $image = $request->file('image');
        $name = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/images');
        $image->move($destinationPath, $name);
        $apartment->image=$name;
        // $apartment->save();
      }

      $apartment->save();

      if ($request->input('services')!==null) {
        $selectedServices = $request->input('services');
        $services = Service::findOrFail($selectedServices);
  
        foreach ($services as $service) {
          $apartment->services()->attach($service);
        }
      }


      return redirect('/');
    }
}
