<script type="text/x-template" id="advanced-search">
  <div class="search-wrapper">
    <div class="form-group address-search-wrapper search-bar">
      <input v-model="latComp" type="hidden" name="lat">
      <input v-model="lonComp" type="hidden" name="lon">
      <div class="close-results-wrapper">
        <input v-on:keyup="pageRealTimeRefresh" class="address-search-spa" type="text" name="address" value="{{$address}}" placeholder="Insert address..."><i class="fas fa-times d-none"></i>
      </div>
      <div class="query-results"></div>
    </div>

      <div class="choose-wrapper d-flex flex-wrap justify-content-center p-4">
          <div class="col-lg-2 p-3">
            <label for="number_of_rooms"><h2>Rooms</h2></label>
            <select @change="optionSelected" name="number_of_rooms">
              @for ($i=0; $i<=10; $i++)
              @php
              if ($i==0) {
                $i='*';
              }
              @endphp
              <option value="{{$i}}"
              @isset($numberOfRooms)
              @if ($numberOfRooms==$i)
              selected
              @endif
              @endisset
              >{{$i}}</option>
              @php
              if ($i=='*') {
                $i=0;
              }
              @endphp
              @endfor
            </select><br>
          </div>

          <div class="col-lg-2 p-3">
            <label for="bedrooms"><h2>Bedrooms</h2></label>
            <select @change="optionSelected" name="bedrooms">
              @for ($i=0; $i<=10; $i++)
              @php
              if ($i==0) {
                $i='*';
              }
              @endphp
              <option value="{{$i}}"
              @isset($bedrooms)
              @if ($bedrooms==$i)
              selected
              @endif
              @endisset
              >{{$i}}</option>
              @php
              if ($i=='*') {
                $i=0;
              }
              @endphp
              @endfor
            </select><br>
          </div>

          <div class="col-lg-2 p-3">
            <label for="radius"><h2>Distanza</h2></label>
            <select @change="optionSelected" name="radius">
              @for ($i=1; $i<=5; $i++)
              <option value="{{$i*200}}"
              @if ($maxDistance==$i*200)
              selected
              @endif
              >{{$i*200}} km</option>
              @endfor
            </select><br>
          </div>
      </div>

    <div class="col-lg-6 service-wrapper">
      <label class="title" for="service">Services</label><br>
      <div class="d-flex justify-content-around service-box">
        @foreach ($services as $service)
        <label><input class="text-center" @change="optionSelected" type="checkbox" name="services[]" value="{{$service->id}}"
        @isset($queryServices)
        @foreach ($queryServices as $queryService)
        @if ($queryService==$service->id)
        checked
        @endif
        @endforeach
        @endisset
        ><br>{{$service->name}}</label><br>
        @endforeach
      </div>
    </div>
  </div>
</script>
