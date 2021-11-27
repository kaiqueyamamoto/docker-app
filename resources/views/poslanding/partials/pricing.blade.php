<section id="pricing" class="py-3">
  <div class="bg-gradient-dark position-relative m-3 border-radius-xl">
    <img src="{{ asset('soft') }}/img/shapes/waves-white.svg" alt="pattern-lines" class="position-absolute start-0 top-md-0 w-100 opacity-6">
    <div class="container pb-lg-9 pb-7 pt-7 postion-relative z-index-2">
      <div class="row">
        <div class="col-md-8 mx-auto text-center">
          <span class="badge bg-gradient-dark mb-2">{{ __('poslanding.pricing') }}</span>
          @if(auth()->user()&&auth()->user()->hasRole('admin'))
            <h3 class="text-primary text-white ckedit" key="pricing_title" id="pricing_title">{{ __('poslanding.pricing_title') }}</h3>
          @else
            <h3 class="text-primary text-white">{{ __('poslanding.pricing_title') }}</h3>
          @endif
          @if(auth()->user()&&auth()->user()->hasRole('admin'))
            <p class="text-primary ckedit text-white" key="pricing_subtitle" id="pricing_subtitle">{{ __('poslanding.pricing_subtitle') }}</p>
          @else
            <p class="text-primary text-white">{{ __('poslanding.pricing_subtitle') }}</p>
          @endif
        </div>
      </div>
    </div>
  </div>
  <div class="mt-n8">
    <div class="container">
      <div class="row">
        @foreach($plans as $key => $plan)
          @include('poslanding.partials.plan',['plan'=>$plan,'col'=>$col,'index'=>$key,'numOfPlans'=>count($plans)])
        @endforeach
      </div>
      <div class="col-md-8 mx-auto text-center mt-4">
        <button  onclick="$('#modal-register').modal('show')" type="button" class="btn btn-primary ">{{ __('poslanding.start_free_now')}}</button>
      </div>
    </div>
  </div>
</section>