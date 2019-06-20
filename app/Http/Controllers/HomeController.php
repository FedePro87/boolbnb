<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Apartment;

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


    // function createNewApartment(){
    
    //     $apartment = Apartment::all();
    //     $services = Services::all();
        
    //     return view('page.add-new-apartment' , compact('apartment','services'));

    // }
}
