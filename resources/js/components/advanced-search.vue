<template>
  <div class="search-wrapper">
    <address-search-component :address="searchedAddress"></address-search-component>

    <div class="address-search-wrapper search-bar row">
      <div class="col-lg-2 p-3 m-4">
        <label for="number_of_rooms"><h2>Rooms</h2></label>
        <select @change="optionSelected" name="number_of_rooms">
          <option v-for="i in 11" :value="i-1">
            <span v-if="i-1==0">*</span>
            <span v-else>{{i-1}}</span>
          </option>
        </select><br>
      </div>

      <div class="col-lg-2 p-3 m-4">
        <label for="bedrooms"><h2>Bedrooms</h2></label>
        <select @change="optionSelected" name="bedrooms">
          <option v-for="i in 11" :value="i-1">
            <span v-if="i-1==0">*</span>
            <span v-else>{{i-1}}</span>
          </option>
        </select><br>
      </div>

      <div class="col-lg-2 p-3 m-4">
        <label for="radius"><h2>Distanza</h2></label>
        <select @change="optionSelected" name="radius">
          <option v-for="i in 5" :value="i*200">{{i*200}} km</option>
        </select><br>
      </div>
    </div>

    <div class="col-lg-6 service-wrapper">
      <label class="title" for="service">Services</label><br>
      <div class="d-flex justify-content-around service-box">
        <div v-for="service in services">
          <label><input class="text-center" @change="checkboxSelected" type="checkbox" name="services[]" :value="service.id"><br>
            {{service.name}}</label><br>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script>

  export default {
    mounted(){

    },
    components: {

    },
    computed: {

    },
    directives:{

    },
    methods: {
      checkboxSelected(e){
        if (e.target.checked) {
          this.selectedCheckboxes.push(e.target.defaultValue);
        } else {
          for( let i = 0; i < this.selectedCheckboxes.length; i=i+1){
            if (this.selectedCheckboxes[i]===e.target.defaultValue) {
              this.selectedCheckboxes.splice(i, 1);
            }
          }
        }

        this.search(true);
      },
      optionSelected(e) {
        switch (e.target.name) {
          case 'number_of_rooms':
          this.realTimeRooms=e.target.value;
          break;
          case 'bedrooms':
          this.realTimeBedrooms=e.target.value;
          break;
          case 'radius':
          this.realTimeRadius=e.target.value;
          break;
        }

        this.eventHub.$emit('search', {
          "number_of_rooms":this.realTimeRooms,
          "bedrooms":this.realTimeBedrooms,
          "radius":this.realTimeRadius,
          "services":this.selectedCheckboxes
        });
      },
    },
    props: {
      rooms: String,
      bedrooms: String,
      radius: String,
      services: Array,
      searchedAddress: String
    },
    filters: {

    },
    data:function(){
      return {
        realTimeRooms: this.rooms,
        realTimeBedrooms: this.bedrooms,
        realTimeRadius: this.radius,
        selectedCheckboxes:[],
      };
    },
  }
  </script>
