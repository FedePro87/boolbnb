<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Apartment;
use App\User;
use App\Message;
use DB;
use App\Service;

class HomeController extends Controller
{
  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    // $this->middleware('auth');
  }

  /**
  * Show the application dashboard.
  *
  * @return \Illuminate\Contracts\Support\Renderable
  */
  public function index()
  {
    return view('home');
  }

  public function abort(){
    return abort(404);
  }

  public function showUserApartments($id){


    $user = User::findOrFail($id);

    return view('page.show-user-apartments', compact('user'));
  }

  public function storeMessage(Request $request,$id){

    if(Auth::user()!==null){
      $request['email']=Auth::user()->email;
    }

    $message = $request-> validate([

      'email' => 'required',
      'title' => 'required',
      'content' => 'required'
    ]);

    $apartment=Apartment::findOrFail($id);
    $message= Message::make($message);
    $message->apartment()->associate($apartment);
    $message->save();
  }

  public function basicSearch(Request $request)
  {
    $lat= $request['lat'];
    $lon= $request['lon'];
    $maxDistance= 20;

    // $R=6371;
    // $maxLat = $lat + rad2deg($rad/$R);
    // $minLat = $lat - rad2deg($rad/$R);
    // $maxLon = $lon + rad2deg(asin($rad/$R) / cos(deg2rad($lat)));
    // $minLon = $lon - rad2deg(asin($rad/$R) / cos(deg2rad($lat)));

    //Fino a qua recupera perfettamente gli appartamenti nel raggio, ma non li ordina per vicinanza.
    // $queryApartments = new Apartment;
    // $queryApartments= $queryApartments->where('lat','>',$minLat)->where('lat','<',$maxLat)->where('lng','>',$minLon)->where('lng','<',$maxLon)->get();

    $latLongQuery= 'SELECT id,title,description,image,address,lat,lng, ((ACOS(SIN(' . $lat . ' * PI() / 180) * SIN(lat * PI() / 180) + COS(' . $lat . '* PI() / 180) * COS(lat * PI() / 180) * COS((' . $lon . ' - lng) * PI() / 180)) * 180 / PI()) * 60 * 1.1515* 1.609344) AS distance FROM apartments HAVING distance <=' . $maxDistance . ' ORDER BY distance ASC';

    $queryApartments = DB::select(DB::raw($latLongQuery));
    $services=Service::all();

    return view('page.show-query-results', compact('queryApartments','services'));;
  }

  // public function showMessageApartment($id){
  //
  //   $apartment = Apartment::findOrFail($id);
  //
  // }
  // function createNewApartment(){

  //     $apartment = Apartment::all();
  //     $services = Services::all();

  //     return view('page.add-new-apartment' , compact('apartment','services'));

  // }
}
