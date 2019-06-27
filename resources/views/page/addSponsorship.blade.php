@extends('layouts.home')
@section('content')

<div class="add-sponsorship">

  @foreach ($sponsorships as $sponsorship)
    <input type="radio" data-sponsorship-id="{{$sponsorship->id}}" name="sponsorship" value="{{$sponsorship->amount}}" @if($sponsorship->id==1)checked="checked"@endif>
      {{$sponsorship->amount}} € per @php echo(floor($sponsorship->duration / 60));@endphp ore di sponsorizzazione.
      </input><br>
  @endforeach

  <div class="container">
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <div id="dropin-container"></div>
          <button id="submit-button">Request payment method</button>
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
             alert('Payment successfull!');
             window.location.href='/';
           } else {
             alert('Payment failed');
           }
         }, 'json');
       });
     });
   });
</script>

@stop