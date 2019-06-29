<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewApartmentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Apartment;
use App\Sponsorship;
use App\Service;
use App\Visual;
use App\User;
use Vendor\autoload;
use DB;
use App;
use Config;
use Session;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Braintree_Gateway;

class ApartmentController extends Controller
{
  public function show($id, Request $request){
    $apartment = Apartment::findOrFail($id);

    //Controllo se ho l'array degli appartamenti visualizzati nella sessione corrente.
    if($request->session()->has('visulized-ids')){
      //Se esiste l'array, controllo che l'id dell'appartamento che sto visualizzando non sia presente (se è presente l'ho
      // evidentemente già visualizzato)
      if(!in_array($id, $request->session()->get('visulized-ids'))){
        //Salvo nell'array della sessione che ho visualizzato l'appartamento.
        $request->session()->push('visulized-ids', $id);
        //Se l'utente che sta guardando è loggato...
        if(Auth::user()!==null){
          //Se non è l'utente a cui appartiente l'appartamento, lo conta come visualizzazione.
          if(Auth::user()->id!==$apartment->user_id){
            $visual= Visual::make();
            $visual->apartment()->associate($apartment);
            $visual->save();
          }
        }
        //Se l'utente non è loggato, lo conta comunque come visualizzazione.
        else {
          $visual= Visual::make();
          $visual->apartment()->associate($apartment);
          $visual->save();
        }
      }
    }
    //Se non ho l'array degli appartamenti visualizzati, procedo alla sua creazione aggiungendo l'id del corrente appartamento.
    // Dopo faccio il solito controllo sull'id dell'utente (se è loggato) per non contare come visualizzazione quella del proprietario.
    else{
      $request->session()->push('visulized-ids', $id);
      if(Auth::user()!==null){
        if(Auth::user()->id!==$apartment->user_id){
          $visual= Visual::make();
          $visual->apartment()->associate($apartment);
          $visual->save();
        }
      } else {
        $visual= Visual::make();
        $visual->apartment()->associate($apartment);
        $visual->save();
      }
    }

    $months = json_encode($this->getMonthsArray());
    $visualsData = json_encode($this->getStatsArray('visuals',$id));
    $messagesData = json_encode($this->getStatsArray('messages',$id));

    return view('page.show-apartment-id', compact('apartment', 'months','visualsData','messagesData'));
  }

  private function getMonthsArray(){
    $startTime = Carbon::now();
    $monthsArray=[];

    foreach (range(-12, 0) as $month) {
      $notFormattedDate= $startTime->copy()->addMonths($month);
      $formattedDate= ucfirst(Carbon::parse($notFormattedDate)->isoFormat('MMMM'));
      $monthsArray[] = $formattedDate;
    }

    return $monthsArray;
  }

  private function getStatsArray($table,$id){
    $startTime = Carbon::now();
    $statsArray=[];

    foreach (range(-12, 0) as $month) {
      $notFormattedDate= $startTime->copy()->addMonths($month);
      $currentMonthNumber=Carbon::parse($notFormattedDate)->isoFormat('OM');
      $currentYear=Carbon::parse($notFormattedDate)->isoFormat('YYYY');

      $statsData = DB::table($table)
      ->where('apartment_id',$id)
      ->whereMonth('created_at', '=', $currentMonthNumber)
      ->whereYear('created_at', '=', $currentYear)
      ->get();

      $statsArray[]=$statsData->count();
    }

    return $statsArray;
  }  

  public function showSponsored(){
    $sponsoreds=[];
    $sponsorships= Sponsorship::all();

    // Per ogni sponsorizzazione ci sarà un timeout diverso, quindi mi vado a prendere il tipo di sponsorizzazione per sapere quanto
    // tempo deve trascorrere.
    foreach ($sponsorships as $sponsorship){
      // Con questa funzione posso prendere qualsiasi data (in questo caso è quella di ADESSO) e diminuirla di tot minuti)
      $diff = Carbon::now()->subMinutes($sponsorship->duration);

      // Utilizzo il wherehas così da andarmi a collegare direttamente con la tabella apartment_sponsorship.
      $apartments = new Apartment;
      $apartments = $apartments->whereHas('sponsorships', function($q)use($sponsorship,$diff){
        // Faccio una query per prendermi solo gli appartamenti che hanno la sponsorizzazione che sto ciclando in questo momento
        $q->where('sponsorship_id', $sponsorship->id);
        //Mi prendo solo quelli che hanno la data successiva alla differenza tra ADESSO e i minuti della sponsorizzazione.
        //IMPORTANTE whereHas ha la caratteristica di considerare come id univoco anche una foreign key (oppure lo fa
        // apposta, chi lo sa!), quindi se becca un'altra colonna con lo stesso apartment_id ignora completamente la precedente
        // e prende in considerazione solo l'ultima
        $q->where('apartment_sponsorship.created_at','>',$diff);
      })->get();

      //Se l'appartamento è già tra gli sponsored, non lo aggiunge. Potrebbe capitare nell'assurdo caso in cui un utente fa
      // prima un pagamento di un tipo e poi di un altro
      foreach ($apartments as $apartment){
        if(!in_array($apartment, $sponsoreds)){
          $sponsoreds[]=$apartment;
        }
      }
    }

    return view('page.sponsored-apartment', compact('sponsoreds'));
  }

  public function apartmentSearch(Request $request){
    $max=6;
    $sponsoreds= $this->showSponsored()->sponsoreds;
    $randIndex = array_rand($sponsoreds, $max);

    for ($i=0; $i < $max; $i++) {
      $sponsoredApartments[]= $sponsoreds[$randIndex[$i]];
    }

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
    // dd($queryApartments);

    if ($advancedSearch) {
      foreach ($queryApartments as $queryApartment) {
        $howMany= $queryApartment->visuals->count();
        $queryApartment->visualized = $howMany;
      }
      return json_encode($queryApartments);
    } else if ($bedrooms!==null&&$numberOfRooms!==null) {
      return view('page.show-query-results', compact('queryApartments','services','address','lat','lon','maxDistance','numberOfRooms','bedrooms','queryServices','sponsoredApartments'));
    } else {
      return view('page.show-query-results', compact('queryApartments','services','address','lat','lon','maxDistance','sponsoredApartments'));

    }
  }

  // Creazione nuovo appartamento - tutto questa roba andrà spostata nell'HomeController
  function createNewApartment(){
    $services = Service::all();
    return view('page.add-apartment',compact('services'));
  }

  function saveNewApartment(NewApartmentRequest $request){
    $validateData = $request -> validated();
    $request->flash();

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

    $positionData = $this->callTomTomApi('https://api.tomtom.com/search/2/geocode/' . $inputAddress . '.JSON',['key'=> "xrIKVZTiqc6NhEvGHRbxYYpsyoLoR2wD",'minFuzzyLevel'=>4,'maxFuzzyLevel'=>4,'limit'=>100],$inputAddress);

    if (!$positionData) {
      return redirect()->back()->withErrors(['Inserisci un indirizzo valido! Puoi utilizzare il menu di scorrimento per aiutarti']);
    } else {
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

      return redirect('/')->withSuccess('Appartamento inserito!');
    }
  }

  private function callTomTomAPI($url, $data, $inputAddress){
    $client = new \GuzzleHttp\Client();
    $response = $client->get($url, ["query" => $data]);

    $incData=json_decode($response->getBody());

    try {
      $results= $incData->results;
      $index= $this->compareInputAddress($results,$inputAddress);
      $result= $incData->results[$index]->position;
    } catch (\Exception $e) {
      return false;
    }

    return $result;
  }

  private function compareInputAddress($results,$inputAddress){
    $index= -1;
    foreach ($results as $key => $result) {
      if ($result->type=="Geography") {
        if(strpos($inputAddress,$result->address->municipality)!==false){
          return $key;
        }
      } else if($result->type=="Cross Street" || $result->type=="Street"){
        if($inputAddress === $result->address->streetName){
          return $key;
        }
      }
    }
    return $index;
  }
}
