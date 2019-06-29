<script type="text/x-template" id="advanced-search">
  <div class="d-flex">
    <div class="form-group address-search-wrapper">
      <input v-model="latComp" type="hidden" name="lat">
      <input v-model="lonComp" type="hidden" name="lon">
      <div class="close-results-wrapper">
        <input v-on:keyup="pageRealTimeRefresh" class="address-search-spa" type="text" name="address" value="{{$address}}" placeholder="Insert address..."><i class="fas fa-times d-none"></i>
      </div>
      <div class="query-results"></div>
    </div>

    <div>
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

    <div>
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

    <div class="ml-4">
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

    <div>
      <div class="d-flex">
        <label for="service">Services</label><br>
        @foreach ($services as $service)
        <input @change="optionSelected" type="checkbox" name="services[]" value="{{$service->id}}"
        @isset($queryServices)
        @foreach ($queryServices as $queryService)
        @if ($queryService==$service->id)
        checked
        @endif
        @endforeach
        @endisset
        ><label>{{$service->name}}</label><br>
        @endforeach
      </div>
    </div>
  </div>
</script>
