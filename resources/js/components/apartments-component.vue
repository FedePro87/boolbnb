<template>
  <div class="d-flex flex-wrap justify-content-center">
    <div class="apartment col-lg-4 p-4" v-if="apartments.length>0" v-for="apartment in apartments">
      <apartment-component :apartment="apartment"></apartment-component>
    </div>
    <h1 v-if="apartments.length==0">Non ci sono risultati!</h1>
  </div>
</template>

<script>
export default {
  mounted(){
    this.eventHub.$on('updateSearchResults', data => {
      this.apartments=data;
    });
  },
  components: {

  },
  computed: {

  },
  methods: {

  },
  props: {
    apartmentsProp: Array
  },
  filters: {

  },
  data:function(){
    return {
      apartments: this.apartmentsProp
    };
  },
}
</script>
