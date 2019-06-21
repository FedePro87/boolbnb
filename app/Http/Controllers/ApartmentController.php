<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewApartmentRequest;
<<<<<<< HEAD
use Illuminate\Http\Request;
use App\Apartment;
use App\Sponsorship;
use App\Service;

use DB;
=======
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Apartment;
use App\Sponsorship;
use App\Service;
use App\User;
>>>>>>> origin/Fede_Prove

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
<<<<<<< HEAD
      if ($request->hasFile('image')) {
        $image = $request->file('image');
        $name = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/images');
        $image->move($destinationPath, $name);
        $this->save();
      }


=======
>>>>>>> origin/Fede_Prove
      $validateData = $request -> validated();

      $apartment = Apartment::make($validateData);
      $inputAuthor= Auth::user()->firstname;
      $user= User::where('firstname','=',$inputAuthor)->first();
      $apartment->user()->associate($user);
<<<<<<< HEAD
      $apartment->save();

      // $apartment = Apartment::create($validateData);
      $apartment->services()->attach($services);


      return redirect('/');
=======

      if ($request->hasFile('image')) {
        $image = $request->file('image');
        $name = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/images');
        $image->move($destinationPath, $name);
        $apartment->image=$name;
      }

      $apartment->save();

      if ($request->input('services')!==null) {
        $selectedServices = $request->input('services');
        $services = Service::findOrFail($selectedServices);

        foreach ($services as $service) {
          $apartment->services()->attach($service);
        }
      }
>>>>>>> origin/Fede_Prove


      return redirect('/');
    }
}


// public function fileUpload(Request $request) {
//   $this->validate($request, [
//       'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//   ]);


//       return back()->with('success','Image Upload successfully');
//   }
// }

// function saveNewPost(NewPostRequest $request){

//   $validateData = $request -> validated();

//   $categoriesId = $validateData['categories'];
//   $categories = Category::find($categoriesId);
//   // dd($validateData);

//   $post = Post::create($validateData);
//   $post->categories()->attach($categories);

//   return redirect('/');

// }
