require('./bootstrap');
var Chart = require('chart.js');

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

function search(searching,index) {
  var query = $('.address-search').val();
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
      if(searching){
        $('.query-results').text("");
        var resultsArray = inData['features'];
        for (var i = 0; i < resultsArray.length; i++) {
          let resultObject = resultsArray[i];
          var newP = document.createElement("p");
          $(newP).text(resultObject['place_name']);
          $(newP).addClass('query-selector');
          $('.query-results').append(newP);
        }
      } else {
        var resultsArray = inData['features'];
        var myQuery = resultsArray[index];
        var myCoordinates = myQuery['center'];
        var lat = myCoordinates[0];
        var lon = myCoordinates[1];
        $("input[name='lat']").attr("data-lat", lat);
        $("input[name='lon']").attr( "data-long", lon );
      }
    },
  error:function(request, state, error){
    console.log(request);
    console.log(state);
    console.log(error);
  }
});
}

function addressRealTimeSearch() {
  $('.address-search').keyup(function() {
    search(true);
  });
}

function init() {
  // Check se esiste il div che contiene la mappa, altrimenti da errori.
  if ($('#map').length){
    addMap();
  }

  if ($('.address-search').length){
    addressRealTimeSearch();
  }

  //I nomi delle label non sono localizzate.
  if ($('#visualsChart').length && $('#messagesChart').length){
    addStatsCharts($('#visualsChart'),'Visuals');
    addStatsCharts($('#messagesChart'),'Messages');
  }

  $(document).on('click','.query-selector',function(){
    var queryName = $(this).text();
    var index = $(this).index();
    $('.address-search').val(queryName);
    $('.query-results').text("");
    search(false,index);
  });
}

$(document).ready(init);
