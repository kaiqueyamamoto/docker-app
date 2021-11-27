<ul class="navbar-nav">
    @if(config('app.ordering'))
       @if(config('settings.is_pos_cloud_mode'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="ni ni-tv-2 text-primary"></i> {{ __('POS') }}
                </a>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link" href="{{ route('orders.index') }}">
                <i class="ni ni-basket text-orangse"></i> {{ __('Orders') }}
            </a>
        </li>
    @endif

</ul>
