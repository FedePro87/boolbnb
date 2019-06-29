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
    $services=Service::all();
    $advancedSearch=$request['advancedSearch'];
    $address = $request['address'];
    $numberOfRooms=$request['number_of_rooms'];
    $bedrooms=$request['bedrooms'];
    $queryServices=$request['services'];
    $lat= $request['lat'];
    $lon= $request['lon'];
    $maxDistance= 20;

    if ($request['radius']!==null) {
      $maxDistance=$request['radius'];
    }

    $queryApartments = Apartment::select('apartments.*')
    ->selectRaw('( 3959 * acos( cos( radians(?) ) *
    cos( radians( lat ) )
    * cos( radians( lng ) - radians(?)
    ) + sin( radians(?) ) *
    sin( radians( lat ) ) )
    ) AS distance', [$lat, $lon, $lat])
    ->havingRaw("distance < ?", [$maxDistance])
    ->orderBy('distance','ASC');

    if ($numberOfRooms!=null && $numberOfRooms!="*") {
      $queryApartments= $queryApartments->where('number_of_rooms',$numberOfRooms);
    }

    if ($bedrooms!=null && $bedrooms!="*") {
      $queryApartments= $queryApartments->where('bedrooms',$bedrooms);
    }

    if ($queryServices!=null) {
      foreach($queryServices as $service){
        $queryApartments = $queryApartments->whereHas('services', function($q)use($service){
          $q->where('service_id', $service); //this refers id field from services table
        });
      }
    }

    $queryApartments= $queryApartments->get();

    if ($advancedSearch) {
      return json_encode($queryApartments);
    } else if ($bedrooms!==null&&$numberOfRooms!==null) {
      return view('page.show-query-results', compact('queryApartments','services','address','lat','lon','maxDistance','numberOfRooms','bedrooms','queryServices'));;
    } else {
      return view('page.show-query-results', compact('queryApartments','services','address','lat','lon','maxDistance'));;
    }
  }
}
