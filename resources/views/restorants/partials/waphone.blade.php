<br />
<h6 class="heading-small text-muted mb-4">{{ __('WhatsApp number') }}</h6>
<!-- Whatsapp phone -->
@include('partials.fields',['fields'=>[
    ['required'=>false,'ftype'=>'input','name'=>'Whatsapp phone', 'placeholder'=>'Whatsapp phone to receive orders on', 'id'=>'whatsapp_phone', 'value'=>$restorant->whatsapp_phone],
]])  