<!--<nav class="navbar navbar-top navbar-horizontal navbar-expand-md bg-white navbar-dark">
    <div class="container-fluid px-7">
        <a class="navbar-brand" href="/">
            <img height="40px" src="{{ config('global.site_logo') }}" />
        </a>
    </div>
</nav>-->
<nav id="navbar-main" class="navbar navbar-top navbar-horizontal navbar-expand-md bg-white navbar-dark">
    <div class="container-fluid px-7">
      <a class="navbar-brand" href="/">
        <img src="{{ config('global.site_logo') }}" />
      </a>
      <!--<div class="navbar-collapse navbar-custom-collapse collapse" id="navbar-collapse">-->
      @if(config('app.isqrsaas') && config('settings.disable_landing'))
        <ul class="navbar-nav align-items-lg-center ml-lg-auto">
          <li class="nav-item ml-lg-4">
            <a href="{{ route('newrestaurant.register') }}" target="_blank" class="btn btn-neutral btn-icon">
              <span class="btn-inner--icon">
                <i class="fas fa-paper-plane mr-2"></i>
              </span>
              <span class="nav-link-inner--text">{{ __('Register') }}</span>
            </a>
          </li>
        </ul>
      @endif
    </div>
</nav>
