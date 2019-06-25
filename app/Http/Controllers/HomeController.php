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

    public function storeMessage(Request $request,$id){
        dd($id);
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

      return view('home');
      }
}
