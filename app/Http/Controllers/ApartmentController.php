<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewApartmentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Apartment;
use App\Sponsorship;
use App\Service;
use App\User;
use Vendor\autoload;
use DB;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class ApartmentController extends Controller
{
 public function show($id)
  {
    $apartment = Apartment::findOrFail($id);
        //   dd($apartment);
          return view('page.show-apartment-id', compact('apartment'));
  }

  public function showSponsored(){
    $apartments= Apartment::all();
    $sponsoreds=[];

    foreach ($apartments as $apartment){
      $apartmentSponsorships = $apartment->sponsorships()->get();
      foreach ($apartmentSponsorships as $apartmentSponsorship){
        $expiringMins=$apartmentSponsorship->type;
        $date = $apartmentSponsorship->pivot->created_at;
        $datework = Carbon::parse($date);
        $now = Carbon::now();
        $diff = $date->diffInMinutes($now);

        if($diff<$expiringMins){
          $sponsoreds[]=$apartment;
        }
      }
    }

    return view('page.sponsored-apartment', compact('sponsoreds'));
  }

    public function search(Request $request){

       $title = $request -> address;
       $services = $request-> services;
       $data=$request->all();
       $apartments= new Apartment;

      if (isset($data['address'])) {
        $apartments = $apartments ->where('address', 'LIKE', '%' . $title . '%');
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
      }

      $inputAddress=$request->input('address');
      // $geocoder = new \OpenCage\Geocoder\Geocoder('7a5d76fa6dcf4bc8ad7ad4dce1115b50');
      // $result = $geocoder->geocode($inputAddress);
      // $lat=$result['results'][0]['geometry']['lat'];
      // $lng=$result['results'][0]['geometry']['lng'];
      // $apartment->lat=$lat;
      // $apartment->lng=$lng;

      $positionData = $this->callTomTomApi('https://api.tomtom.com/search/2/geocode/' . $inputAddress . '.JSON',['key'=> "xrIKVZTiqc6NhEvGHRbxYYpsyoLoR2wD"]);
      $lat=$positionData->lat;
      $lng=$positionData->lon;
      $apartment->lat=$lat;
      $apartment->lng=$lng;
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

    private function callTomTomAPI($url, $data){
    $client = new \GuzzleHttp\Client();
    $response = $client->get($url, ["query" => $data]);

    $incData=json_decode($response->getBody());
    $result=$incData->results[0]->position;

    // dd($incData->results[0]->position);

    return $result;
  }
}
