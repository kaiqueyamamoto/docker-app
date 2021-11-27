<div id="demo" class="section features-1">
  <div class="container">
    <div class="row">
      <div class="col-md-8 mx-auto text-center">
        <span class="badge badge-success badge-pill mb-3">{{ __('whatsapp.demo_badge') }}</span><br/>
        <h3 class="display-3 ckedit" key="demo_title" id="demo_title">{{ __('whatsapp.demo_title') }}</h3>
        <p class="lead ckedit" key="demo_description" id="demo_description">{{ __('whatsapp.demo_description') }}</p>
        <!-- DEMO QR-->
        <br />
        <img class="img-thumbnail img-fluid rounded shadow" data-toggle="tooltip" data-placement="top"
          title="{{ __('whatsapp.demo_image') }}" data-container="body"
          data-animation="true"
          src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{  config('settings.app_url')."/".config('settings.url_route')."/".config('settings.demo_restaurant_slug') }}" />
        <!-- END DEMO QR-->
        <br /> <br />
        <a type="button" target="_blank" class="btn btn-outline-success" href="{{ route('vendor',config('settings.demo_restaurant_slug')) }}">{{__('whatsapp.test_on_web')}}</a>
        
      </div>
    </div>
    <div class="row">


    </div>
  </div>
</div>