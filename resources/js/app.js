require('./bootstrap');
var Chart = require('chart.js');
window.Vue = require('vue');

window.onerror = function(message, source, lineno, colno, error) {
  console.log('Exception: ', error)
}

function addMap() {
  // Qui viene impostata una variabile che rappresenta un array. Rispettivamente ci sono la latitudine e la longitudine. Questi dati possono essere recuperati passando nell'url della show la query o in alternativa nascondendo i dati che ci servono da qualche parte e recuperandoli con jquery.
  var lat=$('#map').data('lat');
  var lng=$('#map').data('lng');
  var myCoordinates = [lat,lng];
  // Questo non è obbligatorio.
  tomtom.setProductInfo('boolbnb', '1.0');
  // Instanzio la variabile map che corrisponde alla mappa che verrà visualizzata. Da notare la chiave center a cui viene dato il valore che corrisponde alle nostre coordinate.
  // Non è localizzata
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

function addStatsCharts(ctx,chartLabel) {
  var stats = ctx.data('stats');
  var monthsLabels = ctx.data('months');
  var myChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: monthsLabels,
      datasets: [{
        label: chartLabel,
        data: stats,
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
        borderColor: 'rgba(255, 99, 132, 1)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true
          }
        }]
      },
      responsive: false,
      maintainAspectRatio: false
    }
  });
}

//Qui viene impostato tutto prima che avvenga la ricerca di lat e lon.
//Sia se siamo nella home che nella ricerca avanzata, la query viene impostata al valore che sta scritto nel rispettivo input.
function search(searching) {
  var query= -1;

  if ($('.address-search').length) {
    query = $('.address-search').val();
  } else {
    query = $('.address-search-spa').val();
  }

  if (query=="") {
    $('.query-results').text("");
  }

  if (!searching && query=="") {
    query="Roma, RM";

    if ($('.address-search').length) {
      $('.address-search').val(query);
    } else {
      $('.address-search-spa').val(query);
    }
  }

  getCoordinates(query,searching);
}



//Recupera le coordinate da mapbox.
function getCoordinates(query,searching) {
  var outData = {
    access_token:"pk.eyJ1IjoiYm9vbGVhbmdydXBwbzQiLCJhIjoiY2p4YnN5N3ltMDdkbjNzcGVsdW54eXFodCJ9.BP8Cf-t-evfHO22_kDFzbg",
    types:"place,address",
    autocomplete:true,
    limit:6
  };

  $.ajax({
    url:'https://api.mapbox.com' + '/geocoding/v5/mapbox.places/' + query +'.json',
    method:"GET",
    data:outData,
    success:function(inData,state){
      //Se stiamo effettuando la ricerca in tempo reale (ovvero se stiamo scrivendo nell'input), arriverà qui.
      //Prima di tutto pialla i risultati precedenti, quindi recupera quelli nuovi e popola di nuovo i risultati in tempo reale.
      if(searching){
        $('.query-results').text("");
        var resultsArray = inData['features'];
        for (var i = 0; i < resultsArray.length; i++) {
          var resultObject = resultsArray[i];
          var newP = document.createElement("p");
          $(newP).text(resultObject['place_name']);

          if (searching=="spa") {
            $(newP).addClass('query-selector-spa');
          } else {
            $(newP).addClass('query-selector');
          }

          $('.query-results').append(newP);
        }

        //Se stiamo effettuando la ricerca avanzata(spa) ma stiamo solo inserendo del testo nell'input, effettua la ricerca eppure allo stesso tempo non fa sparire i risultati che escono sotto. Questo significa che non abbiamo selezionato i risultati di sotto e non esiste un index, dunque pialliamo i risultati che escono sotto.
        if (searching=="spa") {
          if (index!=null) {
            $('.query-results').text("");
          }

          //Se stiamo ancora scrivendo, prende per buono il primo risultato ed effettua la ricerca nel database.
          var myQuery = resultsArray[0];
          var myCoordinates = myQuery['center'];
          var lat = myCoordinates[1];
          var lon = myCoordinates[0];
          $("input[name='lat']").val(lat);
          $("input[name='lon']").val(lon);

          var numberOfRooms = $("select[name='number_of_rooms']").val();
          var bedrooms = $("select[name='bedrooms']").val();
          var radius = $("select[name='radius']").val();

          var services = $("input[name='services[]']:checked").map(function(){
            return $(this).val();
          }).get();

          apartmentsDatabaseSearch(lat,lon,numberOfRooms,bedrooms,radius,services);
        }
      } else {
        //Questo è solo per la ricerca nell'home.
        var resultsArray = inData['features'];
        var myQuery = resultsArray[0];
        var myCoordinates = myQuery['center'];
        var lat = myCoordinates[1];
        var lon = myCoordinates[0];
        $("input[name='lat']").val(lat);
        $("input[name='lon']").val(lon);
      }
    },
    error:function(request, state, error){
      console.log(request);
      console.log(state);
      console.log(error);
    }
  });
}

//Imposta data correttamente e lancia la ricerca nel database.
function apartmentsDatabaseSearch(lat,lon,rooms,bedrooms,radius,services) {
  $.ajax({
    url:"/search",
    method:"GET",
    data:{
      lat:lat,
      lon:lon,
      number_of_rooms: rooms,
      bedrooms: bedrooms,
      radius: radius,
      services:services,
      advancedSearch:1,
    },
    success:function(inData,state){
      var queryContainer=$("#query-apartments");
      queryContainer.text("");
      var resultsArray = JSON.parse(inData);

      if (resultsArray.length==0) {
        var noResultsMessage=document.createElement('h1');
        $(noResultsMessage).text("Non ci sono risultati!");
        queryContainer.append(noResultsMessage);
      } else {
        for (var i = 0; i < resultsArray.length; i++) {
          var resultObject = resultsArray[i];

          var apartmentTemplate=$("#apartment-template").html();
          var compiled=Handlebars.compile(apartmentTemplate);
          var finalApartment=compiled(resultObject);

          queryContainer.append(finalApartment);
        }
      }
    },
    error:function(request, state, error){
      console.log(request);
      console.log(state);
      console.log(error);
    }
  });
}

//Appena viene caricata la home arriva qui, quindi gli facciamo cercare subito (più avanti nel ciclo, imposterà Roma, Rm come query).
//Senza questo workaround, arriverebbe alla pagina di ricerca avanzata con zero risultati.
//Il funzionamento è praticamente copiato da airbnb.
//Quando viene schiacciato un tasto, chiamiamo search impostando true.
function addressRealTimeSearch() {
  search(false);
  $('.address-search').keyup(function() {
    search(true);
  });
}

//Logica che riguarda tutto il pannello di ricerca in tempo reale.
function addAdvancedSearchComponent() {
  Vue.component('advanced-search', {
    template:"#advanced-search",
    props: {
      address: String,
      lat: String,
      lon: String,
      rooms: String,
      bedrooms: String,
      radius: String
    },
    data:function(){
      return {
        latComp: this.lat,
        lonComp: this.lon,
        realTimeAddress: this.address
      };
    },
    computed: {

    },
    methods: {
      pageRealTimeRefresh(){
        search("spa");
      },
      optionSelected() {
        querySelected("spa", $('.address-search-spa').val());
      }
    }
  });

  new Vue({
    el:"#component-vue"
  });
}

//Logica che riguarda il componente appartamento.
function addApartmentComponent() {
  Vue.component('apartment-component', {
    template:"#apartment-component",
    props: {
      description: String,
      image: String,
      altImage: String,
      address: String,
      visuals: Number,
      showIndex: String
    },
    data:function(){
      return {

      };
    },
    computed: {

    },
    methods: {
      changeSrc(event) {
        event.target.src = this.altImage;
      }
    }
  });

  new Vue({
    el:"#apartment-component-wrapper"
  });
}

//Viene chiamata quando si schiacciano i risultati, sia nella home che nella ricerca in tempo reale. Quando accade, viene piallato il contenuto in cui c'erano i risultati. Se stiamo facendo la spa (single page app), chiamiamo search passandogli il termine stesso. Il testo dell'input viene impostato alla voce che abbiamo selezionato.
function querySelected(spa,queryName){
  $('.query-results').text("");

  if (spa) {
    $('.address-search-spa').val(queryName);
    search("spa");
  } else {
    $('.address-search').val(queryName);
    search(false);
  }
}

function init() {
  //Se è presente il wrapper della mappa, raccoglie i dati per popolarlo.
  if ($('#map').length){
    addMap();
  }

  //Se è presente l'input per la ricerca dell'indirizzo, gli dice subito di attivare la possibilità che si vedano i risultati in tempo reale.
  if ($('.address-search').length){
    addressRealTimeSearch();
  }

  //Se verranno aggiunte le statistiche, carica i dati per popolarle.
  if ($('#visualsChart').length && $('#messagesChart').length){
    addStatsCharts($('#visualsChart'),'Visuals');
    addStatsCharts($('#messagesChart'),'Messages');
  }

  //Se siamo nella pagina di ricerca avanzata questo sarà vero.
  if ($('#advanced-search').length) {
    addAdvancedSearchComponent();
  }

  //Tutte le volte che avremo un wrapper degli appartamenti questo sarà vero.
  if ($('#apartment-component-wrapper').length) {
    addApartmentComponent();
  }

  if ($('#save-apartment').length) {
  }

  //Il pulsante base per l'immissione del file è una cosa orrida, quindi l'ho nascosto con un pulsante un tantino più bello.
  //Qui dice che quando viene premuto il pulsante farlocco è come se hai premuto quello "originale"
  $('#fake-upload-image').click(function(){
    $("#upload-image").click();
  });

  //Quando viene innescato il click del pulsante aggiungi immagine succede questo. Praticamente aggiunge l'immagine di anteprima accanto al bottone.
  $("#upload-image").change(function(e) {
    $('.changeImage').remove();
    var file = e.originalEvent.srcElement.files[0];

    var img = document.createElement("img");
    $(img).addClass("changeImage");
    $(img).css("width","100px");
    var reader = new FileReader();
    reader.onloadend = function() {
      img.src = reader.result;
    }
    reader.readAsDataURL(file);
    $("#fake-upload-image").after(img);
  });

  //Quando viene cliccato uno dei risultati della ricerca nella home, viene chiamata la funzione querySelected()
  //Il primo parametro indica se sto effettuando la ricerca in tempo reale oppure no. Il secondo è la query.
  $(document).on('click','.query-selector', function(){
    var queryName = $(this).text();
    querySelected(false,queryName);
  });
  //Analogamente,se clicco nella ricerca in tempo reale passo alla funzione 'true'.
  $(document).on('click','.query-selector-spa', function(){
    var queryName = $(this).text();
    querySelected(true,queryName);
  });
}

$(document).ready(init);
