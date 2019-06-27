<script type="text/x-template" id="apartment-component">
  <div class="apartment col-lg-3">
    <div class="apartment-wrapper">
      <a :href="showIndex">
        <div>
          <img :src="image" class="img-fluid"></img>
        </div>
        <span class="">@{{description}}</span>

        <div>
          <span class="">@{{address}}</span><br>
          <span v-if="visual==1" class="">@{{visual}} visualizzazione</span>
          <span v-else class="">@{{visual}} visualizzazioni</span>
        </div>
      </a>
    </div>
  </div>
</script>
