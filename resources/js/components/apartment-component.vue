<template>
  <div>
    <div class="apartment-wrapper p-5">
      <a :href="showIndex">
        <img :src="apartment.image" @error="changeSrc" class="img-fluid"/>

        <div class="content-apartment">
          <span class="description">{{apartment.description}}</span><br>
          <span class="address">{{apartment.address}}</span><br>
          <span v-if="apartment.visuals.length==1">{{apartment.visuals.length}} visualizzazione</span>
          <span v-else>{{apartment.visuals.length}} visualizzazioni</span>
          <!-- <span class="messages">{{messages}} messaggi</span> -->
        </div>
      </a>
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
    showIndex(){
      return "/show/" + this.apartment.id;
    }
  },
  methods: {
    changeSrc(event) {
      if (event.target.src!=="/" + this.apartment.image) {
        //Qui ci andrà la logica se l'immagine è una di quelle del faker.
      }
    }
  },
  props: {
    apartment: Object
  },
  filters: {

  },
  data:function(){
    return {

    };
  },
}
</script>
