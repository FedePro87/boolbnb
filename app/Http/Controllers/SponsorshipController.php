<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sponsorship;
use App\Apartment;
class SponsorshipController extends Controller
{
    public function showSponsorship($id){

      $apartment = Apartment::findOrFail($id);
      $sponsorships = Sponsorship::all();

      return view('page.addSponsorship', compact('sponsorships', 'apartment'));
    }

   public function updateSponsor(Request $request, $id){



     // Apartment::whereId($id)->update($apartment);
     Apartment::findOrFail($id)->sponsorships()->sync($request['sponsorships']);


     return redirect('/homesponsor');
   }
}
