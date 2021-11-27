<!-- -------- START Features w/ pattern background & stats & rocket -------- -->
<section class="pt-sm-8 pb-5 position-relative bg-gradient-primary overflow-hidden" id="features">
  <div class="position-absolute w-100 z-inde-1 top-0 mt-n3">
    <svg width="100%" viewBox="0 -2 1920 157" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
      <title>wave-down</title>
      <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g id="Artboard" fill="#FFFFFF" fill-rule="nonzero">
          <g id="wave-down">
            <path d="M0,60.8320331 C299.333333,115.127115 618.333333,111.165365 959,47.8320321 C1299.66667,-15.5013009 1620.66667,-15.2062179 1920,47.8320331 L1920,156.389409 L0,156.389409 L0,60.8320331 Z" id="Path-Copy-2" transform="translate(960.000000, 78.416017) rotate(180.000000) translate(-960.000000, -78.416017) "></path>
          </g>
        </g>
      </g>
    </svg>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-8 text-left mb-5 mt-5">
        @if(auth()->user()&&auth()->user()->hasRole('admin'))
          <h3 class="text-white z-index-1 position-relative ckedit" key="features_title" id="features_title">{{ __('poslanding.features_title') }}</h3>
        @else
          <h3 class="text-white z-index-1 position-relative">{{ __('poslanding.features_title') }}</h3>
        @endif
        @if(auth()->user()&&auth()->user()->hasRole('admin'))
          <p class="text-white opacity-8 mb-0 ckedit" key="features_subtitle" id="features_subtitle">{{ __('poslanding.features_subtitle') }}</p>
        @else
          <p class="text-white opacity-8 mb-0">{{ __('poslanding.features_subtitle') }}</p>
        @endif
      </div>
    </div>
    <div class="row mb-9">

      @foreach ($features as $key => $feature)
          <!-- Card  -->
          <div class="col-lg-6 col-12 mt-3">
            <div class="card card-profile mt-lg-0 mt-5 overflow-hidden z-index-2">
              <div class="row">
                <div class="col-lg-4 col-md-6 col-12 pe-lg-0">
                  <a href="javascript:;">
                    <div class="p-3 pe-0">
                    <img 
                      class="w-100 border-radius-md" 
                      src='{{ str_contains($feature->image, "soft") ? $feature->image : "/uploads/restorants/".$feature->image."_large.jpg" }}' 
                    >
                    </div>
                  </a>
                </div>
                <div class="col-lg-8 col-md-6 col-12 ps-lg-0 my-auto">
                  <div class="card-body">
                    <h5 class="mb-0">{{ $feature->title }}</h5>
                    <h6 class="text-info">{{ $feature->subtitle }}</h6>
                    <p>{{ $feature->description }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- End card -->
      @endforeach

  
    </div>

  </div>
  <div class="position-absolute w-100 bottom-0">
    <svg width="100%" viewBox="0 -1 1920 166" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
      <title>wave-up</title>
      <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g id="Artboard" transform="translate(0.000000, 5.000000)" fill="#FFFFFF" fill-rule="nonzero">
          <g id="wave-up" transform="translate(0.000000, -5.000000)">
            <path d="M0,70 C298.666667,105.333333 618.666667,95 960,39 C1301.33333,-17 1621.33333,-11.3333333 1920,56 L1920,165 L0,165 L0,70 Z" id="Path" fill="#fff"></path>
          </g>
        </g>
      </g>
    </svg>
  </div>
</section>
<!-- -------- END Features w/ pattern background & stats & rocket -------- -->