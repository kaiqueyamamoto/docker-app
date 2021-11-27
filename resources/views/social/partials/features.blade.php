<div id="features" class="section features-6">
      <div class="container">
        <div class="row">
        @foreach ($features as $key => $feature)
          <!-- Card  -->
          <div class="col-sm-12 col-md-6 col-lg-4 mb-4 ">
            <div class="card cardWithShadow cardWithShadowAnimated">
              <div class="card-body">
                <div class="imgHolderInCard">
                  <img 
                    class="image-in-card" 
                    src='{{ str_contains($feature->image, "social") ? $feature->image : "/uploads/restorants/".$feature->image."_large.jpg" }}' 
                  />
                </div>
                <h5 class="card-title">{{ $feature->title }}</h5>
                <p class="card-text">{{ $feature->description }}</p>
              </div>
            </div>
          </div>
          <!-- End card -->
          @endforeach
        </div>

        <br />

        <div class="container">
          <div class="row">
            <div class="col-md-8 mx-auto text-center">
              <div class="alert alert-success ckedit" role="alert" key="feature_main_subtitle1" id="feature_main_subtitle1">{{ __('whatsapp.feature_main_subtitle1') }}</div>
              <div class="alert alert-primary ckedit" role="alert" key="feature_main_subtitle2" id="feature_main_subtitle2">{{ __('whatsapp.feature_main_subtitle2') }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>