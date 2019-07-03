@extends('layouts.home')
@section('content')

  @include('layouts.header')
<div class="add-sponsorship">

 <div class="price-block">

  <h2 id="title">Sponsorizza la stanza '{{$apartment->title}}'</h2>
  <h4>Scegli la modalità di sponsorizzazione:</h4>

  <div class="sponsorship-type">

  @foreach ($sponsorships as $sponsorship)
    <div class="type">

    <input type="radio" data-sponsorship-id="{{$sponsorship->id}}" name="sponsorship" value="{{$sponsorship->amount}}" @if($sponsorship->id==1)checked="checked"@endif>
      <strong>{{$sponsorship->amount}}</strong> € per @php echo(floor($sponsorship->duration / 60));@endphp ore di sponsorizzazione.
      </input><br>
    </div>
  @endforeach

  </div>

</div>

  <div class="container payment-form">
      <div class="row">
        <div class="col-md-8 col-md-offset-2 mx-auto my-5">
          <div id="dropin-container"></div>
          <button id="submit-button" class="boolbnb-btn">Procedi al pagamento</button>
        </div>
      </div>
   </div>
</div>

<script>
  var url= '{{ route('payment.process',['amount','apartment-id','sponsorship-id']) }}';
  var amount= $("input[name='sponsorship']:checked").val();
  var sponsorshipId = $("input[name='sponsorship']:checked").data('sponsorship-id');
  url = url.replace('amount',amount);
  url = url.replace('apartment-id',{{$apartment->id}});
  url = url.replace('sponsorship-id',sponsorshipId);
  var button = document.querySelector('#submit-button');
  braintree.dropin.create({
     authorization: "{{ Braintree_ClientToken::generate() }}",
     container: '#dropin-container'
   }, function (createErr, instance) {
     button.addEventListener('click', function () {

       instance.requestPaymentMethod(function (err, payload) {

         $.get(url, {payload}, function (response) {
           if (response.success) {
             alert('Pagamento riuscito!');
             window.location.href='/';
           } else {
             alert('Pagamento rifiutato');
           }
         }, 'json');
       });
     });
   });
</script>

@stop
