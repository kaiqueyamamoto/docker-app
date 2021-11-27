@extends('layouts.app', ['title' => __('Settings')])
@section('js')
<script src="https://gumroad.com/js/gumroad.js"></script>
@endsection
@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
</div>
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <h3 class="mb-0">{{ __('Apps') }}</h3>
                        </div>
                        @if (config('settings.is_demo') | config('settings.is_demo')) 
                        <div class="col-8 text-right">
                            <a  onclick="alert('Disabled in demo')" class="btn btn-sm btn-success text-white">{{ __('Add new') }}</a>
                        </div>
                        @else
                            <div class="col-8 text-right">
                                <a  onclick="$('#appupload').click();" class="btn btn-sm btn-success text-white">{{ __('Add new') }}</a>
                            </div>
                        @endif
                        
                    </div>
                </div>
                <div class="card-body">
                    @include('partials.flash')
                    <form method="post" enctype="multipart/form-data">
                        @csrf
                        <div style="display: none">
                            <input name="appupload" type="file" class="" id="appupload" accept=".zip,.rar,.7zip"   onchange="form.submit()">
                        </div>
                    </form>

                    <div class="row">
                        @if(empty($apps))
                        <p>
                            {{ __("There are no apps at the moment")}}
                        </p>
                        @endif
                        @foreach ($apps as $app)
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 mt-3">
                            <div class="card" style="width: 18rem;">
                                <img class="card-img-top" src="{{ $app->image }}" alt="{{ $app->name }}">
                                <div class="card-body">
                                <h5 class="card-title">{{ $app->name }} @if ($app->installed)<span class="small text-green">{{ __('installed')}} v{{$app->version}}</span>@endif</h5>
                                <p class="card-text">{{ $app->description }}</p>
                                @if ($app->installed)
                                    <a href="{{ route('settings.index') }}" class="btn btn-success">{{ __('Settings')}}</a>
                                @else
                                @if (isset($app->gr))
                                    <a class="gumroad-button" href="https://gumroad.com/l/{{$app->gr}}">
                                        @if ($app->price=="Free")
                                            {{ __('Download for free') }}
                                        @else
                                            {{ __('Buy now')." - ".$app->price }}
                                        @endif
                                    </a>  
                                @else
                                    <a target="_blank" href="{{ $app->link }}" class="btn btn-primary">{{ __('Buy now')." - ".$app->price }}</a>
                                @endif
                               
                                @endif
                                
                                </div>
                            </div>  
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection