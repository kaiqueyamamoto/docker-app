<div class="col-sm-12 col-md-{{$col}} col-lg-{{$col}} mb-{{$col}} ">
  <div class="card cardWithShadow pricingCard text-center">
    <div class="card-body">
      <!-- <div class="imgHolderInCard">
        <img class="image-in-card"
          src="{{ asset('social') }}/img/SVG/512/rocket.svg" />
      </div> -->
      <h5 class="card-title">{{  __($plan['name']) }}</h5>
      <p class="card-text">{{ __($plan['description']) }}</p>
      <div class="price-block">
        <span class="price-block-currency">{{ config('settings.cashier_currency') }}</span>
        <span class="price-block-value">{{ $plan['price'] }}</span>
        <span class="price-block-period">/{{  $plan['period'] == 1? __('whatsapp.month') :  __('whatsapp.year') }}</span>
      </div>
      <div class="plan_feature_list">
        <ul class="plan_features list-unstyled align-items-center">
          @foreach (explode(",",$plan['features']) as $feature)
            <li>
              <p class="text-sm">{{ __($feature) }}</p>
            </li>
          @endforeach
        </ul>
      </div>
      <br />
      <a href="{{ route('newrestaurant.register') }}" type="button" class="btn btn-outline-success">
        {{ __('whatsapp.start_now')}}
      </a>
    </div>
  </div>
</div>