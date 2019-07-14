require('./bootstrap');
var Chart = require('chart.js');
window.Vue = require('vue');
Vue.component('advanced-search', require('./components/advanced-search').default);
Vue.component('apartments-component', require('./components/apartments-component').default);
Vue.component('apartment-component', require('./components/apartment-component').default);
Vue.component('address-search-component', require('./components/address-search-component').default);

const eventHub = new Vue() // Single event hub

// Distribute to components using global mixin
Vue.mixin({
  data: function () {
    return {
      eventHub: eventHub
    }
  }
});

function addAddressSearchComponent() {
  const addressSearchComponent = new Vue({
    el: '#address-search-component-wrapper'
  });
}

function addAdvancedSearchComponent() {
  const advancedSearchComponent = new Vue({
    el: '#advanced-search-component-wrapper'
  });
}

function addApartmentsComponent() {
  const apartmentsComponent = new Vue({
    el: '#apartments-component-wrapper'
  });
}

function addApartmentComponent(){
  const apartmentComponent = new Vue({
    el: '#apartment-component-wrapper'
  });
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
        backgroundColor: ' #FF5A5F',
        borderColor: ' #FF5A5F',
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

function init() {
  $('.alert').fadeOut(10000);
  //Se è presente il wrapper della mappa, raccoglie i dati per popolarlo.
  if ($('#map').length){
    addMap();
  }

  //Se verranno aggiunte le statistiche, carica i dati per popolarle.
  if ($('#visualsChart').length && $('#messagesChart').length){
    addStatsCharts($('#visualsChart'),'Visuals');
    addStatsCharts($('#messagesChart'),'Messages');
  }

  //Se siamo nella pagina di ricerca avanzata questo sarà vero.
  if ($('#advanced-search-component-wrapper').length) {
    addAdvancedSearchComponent();
  }

  //Tutte le volte che avremo un wrapper degli appartamenti questo sarà vero (ricerca avanzata).
  if ($('#apartments-component-wrapper').length) {
    addApartmentsComponent();
  }

  //Questo sarà vero nella home.
  if ($('#apartment-component-wrapper').length) {
    addApartmentComponent();
  }

  //Aggiunge la barra di ricerca indirizzi se è presente il suo wrapper.
  if ($('#address-search-component-wrapper').length) {
    addAddressSearchComponent();
  }

  //Fa in modo che la navbar vari tra invisibile e visibile nella home.
  $(function(e) {
    $(window).scroll(function(e) {
      if ($(".navbar").offset().top>=600) {
        $('.navbar').addClass('original-header');
      }
      if ($(".navbar").offset().top<=600) {
        $('.navbar').removeClass('original-header');
      }
    });
  });

  //Specifica cosa avviene quando viene cliccato l'indirizzo mail all'interno della lista dei messaggi.
  $('.emailLink').on('click', function (event) {
    event.preventDefault();
    url = 'mailto:' + $(this).data('mail') + '?subject=Risposta al messaggio su BoolBnB per appartamento ' + "'" + $(this).data('title') + "'";
    var win = window.open(url, '_blank');
    win.focus();
  });
}

$(document).ready(init);
