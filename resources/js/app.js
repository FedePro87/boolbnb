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

function search(searching,index) {
  var query= -1;

  if ($('.address-search').length) {
    query = $('.address-search').val();
  } else {
    query = $('.address-search-spa').val();
  }

  if (query=="") {
    $('.query-results').text("");
  }

  getCoordinates(query,searching,index);
}

  // if (!searching && query=="") {
  //   query="Roma, RM";
  //   getCoordinates(query,searching,index);
  // }

  function getCoordinates(query,searching,index) {
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

          if (searching=="spa") {
            if (index!=null) {
              $('.query-results').text("");
            }

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

  function addressRealTimeSearch() {
    $('.address-search').keyup(function() {
      search(true);
    });
  }

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
          querySelected("spa", $('.address-search-spa').val(),$("select[name='number_of_rooms']").val());
        }
      }
    });

    new Vue({
      el:"#component-vue"
    });
  }

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

  function querySelected(spa,queryName,index){
    $('.query-results').text("");

    if (spa) {
      $('.address-search-spa').val(queryName);
      search("spa",index);
    } else {
      $('.address-search').val(queryName);
      search(false,index);
    }
  }

  function init() {
    if ($('#map').length){
      addMap();
    }

    if ($('.address-search').length){
      search(false);
      addressRealTimeSearch();
    }

    if ($('#visualsChart').length && $('#messagesChart').length){
      addStatsCharts($('#visualsChart'),'Visuals');
      addStatsCharts($('#messagesChart'),'Messages');
    }

    if ($('#advanced-search').length) {
      addAdvancedSearchComponent();
    }

    if ($('#apartment-component-wrapper').length) {
      addApartmentComponent();
    }

    $('#fake-upload-image').click(function(){
      $("#upload-image").click();
    });
    $('#upload-image').change(function() {
      $('#selected_filename').text($('#fileinput]')[0].files[0].name);
    });

    $(document).on('click','.query-selector', function(){
      var queryName = $(this).text();
      var index = $(this).index();
      querySelected(false,queryName,index);
    });
    $(document).on('click','.query-selector-spa', function(){
      var queryName = $(this).text();
      var index = $(this).index();
      querySelected(true,queryName,index);
    });
  }

  $(document).ready(init);
