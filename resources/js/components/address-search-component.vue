<template>
  <div class="form-group address-search-wrapper search-bar">
    <input v-model="latComp" type="hidden" name="lat">
    <input v-model="lonComp" type="hidden" name="lon">
    <div class="close-results-wrapper">
      <input v-on:keyup="pageRealTimeRefresh" @click="pageRealTimeRefresh" class="address-search-spa" type="text" name="address" v-model="realTimeAddress" placeholder="Insert address..." v-clickOutside><i ref="closeResultsButton" v-if="showFaTimes" class="fas fa-times" @click="timesClicked"></i>
    </div>
    <div class="query-results">
      <div v-for="result in results" class="query-selector-spa" @click="realTimeResultSelected">{{result}}</div>
    </div>
  </div>
</template>

<script>

export default {
  mounted(){
    this.eventHub.$on('search', data => {
      this.search(true,data);
    });

    if (this.address==null) {
      this.realTimeAddress="Roma, RM";
    }

    if (this.homeSearch) {
      this.search(true);
    }
  },
  components: {

  },
  computed: {

  },
  directives:{
    clickOutside:{
      bind (el, binding, vnode) {
        let handleOutsideClick = (e) => {
          e.stopPropagation();
          //FIXME La classe esclusa è troppo scriptata.
          if (el!==e.target && e.target.className!=="fas fa-times") {
            vnode.context.showFaTimes=false;
            vnode.context.results=[];
          }
        }

        document.addEventListener('click', handleOutsideClick);
        document.addEventListener('touchstart', handleOutsideClick);
      },
      unbind () {
        document.removeEventListener('click', handleOutsideClick);
        document.removeEventListener('touchstart', handleOutsideClick);
      }
    }
  },
  methods: {
    //Cosa fa se si seleziona la x nella input.
    timesClicked(){
      this.showFaTimes=false;
      this.realTimeAddress="";
    },
    //Cosa fa se si schiaccia la input o se si scrive nella input.
    pageRealTimeRefresh(){
      if (this.realTimeAddress==="") {
        this.showFaTimes=false;
      } else {
        this.showFaTimes=true;
      }
      this.search();
    },
    //Cosa fa se si seleziona uno degli indirizzi nella tendina.
    realTimeResultSelected(e){
      let selectedAddress=$(e.target).text();
      this.realTimeAddress=selectedAddress;
      this.search(true);
    },
    //Funzione che si occupa di popolare la tendina dei risultati in tempo reale, e subito dopo di ricercare all'interno del database secondo i diversi criteri.
    search(addressesSelected,advancedData){
      let self= this;
      let query=this.realTimeAddress;

      const outData = {
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
          self.results=[];
          let resultsArray = inData['features'];

          //Crea i risultati di ricerca in tempo reale.
          for (let i = 0; i < resultsArray.length; i=i+1) {
            let resultAddress=resultsArray[i]['place_name'];
            //Popola di nuovo i risultati soltanto se sto digitando un indirizzo. Non lo fa in tutti gli altri casi.
            if (!addressesSelected) {
              self.results.push(resultAddress);
            }
          }

          let myQuery = resultsArray[0];
          let myCoordinates = myQuery['center'];
          let lat = myCoordinates[1];
          let lon = myCoordinates[0];
          self.latComp =lat;
          self.lonComp =lon;

          //Se è nella pagina iniziale, si è ormai salvato già i dati sul componente (indirizzo, lat e lon), quindi può tranquillamente finire qui.
          if (self.homeSearch) {
            return true;
          }

          if (advancedData!=null) {
            var numberOfRooms = advancedData.number_of_rooms;
            var bedrooms = advancedData.bedrooms;
            var radius = advancedData.radius;
            var services = advancedData.services;
          } else {
            var numberOfRooms = 1;
            var bedrooms = 1;
            var radius = 1;
            var services = [];
          }

          console.log(lat,lon,numberOfRooms,bedrooms,radius,services);

          self.apartmentsDatabaseSearch(lat,lon,numberOfRooms,bedrooms,radius,services);
        },
        error:function(request, state, error){
          console.log(request);
          console.log(state);
          console.log(error);
        }
      });
    },
    apartmentsDatabaseSearch(lat,lon,rooms,bedrooms,radius,services){
      let self=this;
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
          advancedSearch:self.advancedSearch,
        },
        success:function(inData,state){
          console.log("indata" + inData);
          let resultsArray = JSON.parse(inData);
          console.log(resultsArray);

          self.eventHub.$emit('updateSearchResults', resultsArray);
        },
        error:function(request, state, error){
          console.log(request);
          console.log(state);
          console.log(error);
        }
      });
    }
  },
  props: {
    address: String,
    lat: String,
    lon: String,
    homeSearch: Boolean
  },
  filters: {

  },
  data:function(){
    return {
      latComp: this.lat,
      lonComp: this.lon,
      realTimeAddress: this.address,
      results: [],
      showFaTimes:false
    };
  },
}
</script>
