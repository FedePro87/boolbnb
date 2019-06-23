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

function init() {
  // Check se esiste il div che contiene la mappa, altrimenti da errori.
  if ($('#map').length){
    addMap();
  }

  //I nomi delle label non sono localizzate.
  if ($('#visualsChart').length && $('#messagesChart').length){
    addStatsCharts($('#visualsChart'),'Visuals');
    addStatsCharts($('#messagesChart'),'Messages');
  }
}

$(document).ready(init);
