<footer class="footer">
      <div class="container">
        <div class="row row-grid align-items-center mb-5">
          <div class="col-lg-6">
            @if (config('settings.enable_default_cookie_consent'))
              @include('cookieConsent::index')
            @endif
            <h3 class="text-primary font-weight-light mb-2 ckedit" key="footer_title" id="footer_title">{{ __('whatsapp.footer_title') }}</h4></h3>
            <h4 class="mb-0 font-weight-light ckedit" key="footer_subtitle" id="footer_subtitle">{{ __('whatsapp.footer_subtitle') }}</h4>
          </div>
          <div class="col-lg-6 text-lg-center btn-wrapper">
            <!--<button target="_blank" href="#" rel="nofollow"
              class="btn btn-icon-only btn-twitter rounded-circle" data-toggle="tooltip"
              data-original-title="Follow us">
              <span class="btn-inner--icon"><i class="fa fa-twitter"></i></span>
            </button>-->
            @if(config('global.facebook'))
            <a target="_blank" href="{{ config('global.facebook') }}" rel="nofollow"
              class="btn-icon-only rounded-circle btn btn-facebook" data-toggle="tooltip" data-original-title="Like us">
              <span class="btn-inner--icon"><i class="fab fa-facebook"></i></span>
            </a>
            @endif
            <!--<button target="_blank" href="#" rel="nofollow"
              class="btn-icon-only rounded-circle btn btn-youtube" data-toggle="tooltip" data-original-title="Like us">
              <span class="btn-inner--icon"><i class="fab fa-youtube"></i></span>
            </button>-->
            @if(config('global.instagram'))
            <a target="_blank" href="{{ config('global.instagram') }}" rel="nofollow"
              class="btn-icon-only rounded-circle btn btn-instagram" data-toggle="tooltip" data-original-title="Like us">
              <span class="btn-inner--icon"><i class="fab fa-instagram"></i></span>
            </a>
            @endif
          </div>
        </div>
        <hr>
        <div class="row align-items-center justify-content-md-between">
          <div class="col-md-6">
            <div class="copyright">
              &copy; {{ date('Y')}} <a href="" target="_blank">{{ config('app.name') }}</a>.
            </div>
          </div>
          <div class="col-md-6">
            <ul class="nav nav-footer justify-content-end">
             
                    
              @foreach ($pages as $page)
                <li class="nav-item">
                  <a href="/blog/{{ $page->slug }}" class="nav-link" target="_blank">{{ $page->title }}</a>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </footer>