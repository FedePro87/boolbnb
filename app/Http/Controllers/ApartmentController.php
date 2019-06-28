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

      if(isset($data['services'])){
        foreach($data['services'] as $service){
          $apartments = $apartments->whereHas('services', function($q)use($service){

            $q->where('service_id', $service); //this refers id field from services table

          });
        }

        return $statsArray;
      }
    }
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
    $services = Service::all();
    return view('page.add-apartment',compact('services'));
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

    return $result;
  }
}
