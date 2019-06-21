require('./bootstrap');

// function searchLatLong(){
//   var inputData = {
//     key: "xrIKVZTiqc6NhEvGHRbxYYpsyoLoR2wD",
//     countrySet:'IT'
//   };

//   $.ajax({
//     url: "https://api.tomtom.com/search/2/geocode/" + query + ".JSON",
//     data:inputData,
//     success:function(result){
//       console.log(result);
//    },
//    error: function(request,state,errors){
//     console.log(request);
//     console.log(state);
//     console.log(errors);      
//    }

//     // [&limit=<limit>][&radius=<radius>][&language=<language>]
//   });
// }

function addMap() {
  // Qui viene impostata una variabile che rappresenta un array. Rispettivamente ci sono la latitudine e la longitudine. Questi dati possono essere recuperati passando nell'url della show la query o in alternativa nascondendo i dati che ci servono da qualche parte e recuperandoli con jquery.
  var lat=$('#map').data('lat');
  var lng=$('#map').data('lng');
  console.log(lat);
  var myCoordinates = [lat,lng];
  // Questo non è obbligatorio.
  tomtom.setProductInfo('boolbnb', '1.0');
  // Instanzio la variabile map che corrisponde alla mappa che verrà visualizzata. Da notare la chiave center a cui viene dato il valore che corrisponde alle nostre coordinate.
  var map= tomtom.L.map('map', {
    key: 'xrIKVZTiqc6NhEvGHRbxYYpsyoLoR2wD',
    source: 'vector',
    basePath: '/tomtom-sdk',
    center: myCoordinates,
    zoom: 16,
    language: "it-IT"
  });
  // Qui inserisco anche un marker che viene posizionato esattamente sull'abitazione.
  var marker = tomtom.L.marker(myCoordinates).addTo(map);
  marker.bindPopup('Appartamento').openPopup();
}

function init() {
  // searchLatLong();
  // Check se esiste il div che contiene la mappa, altrimenti da errori
  if ($('#map').length){
    addMap();
  }
}

$(document).ready(init);
