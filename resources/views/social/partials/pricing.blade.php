<div id="pricing" class="section pricing_section">
  <div class="container">
    <div class="row">
      <div class="col-md-8 mx-auto text-center">
        <h3 class="display-3 ckedit" key="pricing_title" id="pricing_title">{{ __('whatsapp.pricing_title') }}</h3>
        <p class="lead ckedit" key="pricing_subtitle" id="pricing_subtitle">{{ __('whatsapp.pricing_subtitle') }}</p><br />
      </div>
    </div>

    <div class="row">
      @foreach ($plans as $plan)
          @include('social.partials.plan',['plan'=>$plan,'col'=>$col])
      @endforeach
    </div>
  </div>
</div>