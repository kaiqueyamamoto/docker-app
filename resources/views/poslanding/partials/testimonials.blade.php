<section class="py-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 mx-auto text-center">
        @if(auth()->user()&&auth()->user()->hasRole('admin'))
          <h2 class="text-gradient text-primary mb-0 ckedit" key="testimonials_title" id="testimonials_title">{{ __('poslanding.testimonials_title') }}</h2>
        @else
          <h2 class="text-gradient text-primary mb-0">{{ __('poslanding.testimonials_title') }}</h2>
        @endif
        @if(auth()->user()&&auth()->user()->hasRole('admin'))
          <h2 class="mb-3 ckedit" key="testimonials_subtitle" id="testimonials_subtitle">{{ __('poslanding.testimonials_subtitle') }}</h2>
        @else
          <h2 class="mb-3">{{ __('poslanding.testimonials_subtitle') }}</h2>
        @endif
        @if(auth()->user()&&auth()->user()->hasRole('admin'))
          <p class="ckedit" key="testimonials_description" id="testimonials_description">{{ __('poslanding.testimonials_description') }}</p>
        @else
          <p>{{ __('poslanding.testimonials_description') }}</p>
        @endif
      </div>
    </div>
    <div class="row mt-6">
      @foreach ($testimonials as $key => $testimonial)
      <?php $highlited=rand(0,1) == 1; $highlited=0; ?>
        <div class="col-lg-4 col-md-8">
          <div class="{{$highlited ? 'card bg-gradient-primary' : 'card card-plain'}}">
            <div class="card-body">
              <div class="author">
                <img src='{{ str_contains($testimonial->image, "social") ? $testimonial->image : "/uploads/restorants/".$testimonial->image."_large.jpg" }}' alt="..." class="avatar shadow">
                <div class="name ps-2">
                  <span class="{{$highlited ? 'text-white' : ''}}">{{ $testimonial->title }}</span>
                  <div class="stats">
                    <small class="{{$highlited ? 'text-white' : ''}}"><i class="far fa-clock"></i> {{ $testimonial->subtitle }}</small>
                  </div>
                </div>
              </div>
              <p class="{{ $highlited ? 'mt-4 text-white' : 'mt-4'}}">"{{ $testimonial->description }}"</p>
              <div class="rating mt-3">
                <i class="{{ $highlited ? 'fas fa-star text-white' : 'fas fa-star'}}"></i>
                <i class="{{ $highlited ? 'fas fa-star text-white' : 'fas fa-star'}}"></i>
                <i class="{{ $highlited ? 'fas fa-star text-white' : 'fas fa-star'}}"></i>
                <i class="{{ $highlited ? 'fas fa-star text-white' : 'fas fa-star'}}"></i>
                <i class="{{ $highlited ? 'fas fa-star text-white' : 'fas fa-star'}}"></i>
              </div>
            </div>
          </div>
          <br/>
        </div>
      @endforeach
    </div>
  </div>
</section>