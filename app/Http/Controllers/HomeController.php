<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Apartment;
use App\User;
use App\Message;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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

    public function showUserApartments($id){


      $user = User::findOrFail($id);

      // $apartments = new Apartment;
      // $apartments = $apartments->where('user_id', $id)->get();
      return view('page.show-user-apartments', compact('user'));


    }

    public function storeMessage(Request $request){

    $message = $request-> validate([

      'email' => 'required',
      'title' => 'required',
      'content' => 'required'
    ]);

    $inputAuthor= Auth::user()->email;
    $user= User::where('email','=',$inputAuthor)->first();
    $apartment->user()->associate($user);
    $message = Message::create($message);


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
