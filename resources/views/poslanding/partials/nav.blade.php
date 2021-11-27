  <!-- Navbar Transparent -->
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
        <nav class="navbar navbar-expand-lg  blur blur-rounded top-0 z-index-fixed shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
          <div class="container-fluid">
            <a href="/"> 
                <img src="{{ config('global.site_logo') }}"  height="40">
              </a>
            <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon mt-2">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </span>
            </button>
            <div class="collapse navbar-collapse pt-3 pb-2 py-lg-0 w-100" id="navigation">
              <ul class="navbar-nav navbar-nav-hover ms-lg-12 ps-lg-5 w-100">
                <li class="nav-item dropdown dropdown-hover mx-2">
                  <a class="nav-link ps-2 d-flex justify-content-between cursor-pointer align-items-center" data-scroll href="#product" aria-expanded="false">
                    {{ __('poslanding.product') }}
                  </a>
                </li>
                <li class="nav-item dropdown dropdown-hover mx-2">
                  <a class="nav-link ps-2 d-flex justify-content-between cursor-pointer align-items-center" data-scroll href="#features" aria-expanded="false">
                    {{ __('poslanding.features') }}
                  </a>
                </li>
                <li class="nav-item dropdown dropdown-hover mx-2">
                  <a class="nav-link ps-2 d-flex justify-content-between cursor-pointer align-items-center" data-scroll href="#pricing"  aria-expanded="false">
                    {{ __('poslanding.pricing') }}
                  </a>
                </li>
                @if(count($availableLanguages)>1)
                  <li class="nav-item dropdown dropdown-hover mx-2">
                    <a class="nav-link ps-2 d-flex justify-content-between cursor-pointer align-items-center" id="dropdownLanguages" data-bs-toggle="dropdown" aria-expanded="false">
                      @foreach ($availableLanguages as $short => $lang)
                        @if(strtolower($short) == strtolower($locale)) <span class="nav-link-inner--text"> {{ $lang }}</span>@endif
                      @endforeach
                      <img src="./soft/img/down-arrow-dark.svg" alt="down-arrow" class="arrow ms-1">
                      
                      <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                        @foreach ($availableLanguages as $short => $lang)
                        <li>
                            <a class="dropdown-item" href="/{{ strtolower($short) }}">
                              
                               {{ $lang }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    </a>
                  </li>
                @endif
                <li class="nav-item ms-lg-auto">
                  <a class="nav-link nav-link-icon me-2" href="{{ route('home')}}">
                    @auth()
                      <i class="fa fa-th-large me-1"></i>
                      <p class="d-inline text-sm z-index-1 font-weight-bold">{{ __('poslanding.dashboard') }}</p>
                    @endauth
                    @guest()
                      
                      <p class="d-inline text-sm z-index-1 font-weight-bold">{{ __('poslanding.login') }}</p>
                    @endguest
                  </a>
                </li>
                @guest
                  <li class="nav-item my-auto ms-3 ms-lg-0">
                    <button type="button" class="btn btn-sm  bg-gradient-primary  btn-round mb-0 me-1 mt-2 mt-md-0" onclick="$('#modal-register').modal('show')">
                      <span class="nav-link-inner--text">{{ __('poslanding.register')}}</span>
                    </button>
                  </li>
                @endguest

                

              </ul>
            </div>
          </div>
        </nav>
        <!-- End Navbar -->
      </div>
    </div>
  </div>
  <!-- End Navbar -->