<script type="text/x-template" id="apartment-component">
  <div class="apartment col-lg-3">
    <div class="apartment-wrapper">
      <a :href="showIndex">
        <div>
          <img :src="image" @@error="changeSrc" class="img-fluid"/>
        </div>

        <div class="content-apartment">
          <span class="description">@{{description}}</span><br>
          <span class="address">@{{address}}</span><br>
          <span v-if="visuals==1" class="">@{{visuals}} visualizzazione</span>
          <span v-else class="">@{{visuals}} visualizzazioni</span>
        </div>
      </a>
    </div>
  </div>
</script>
