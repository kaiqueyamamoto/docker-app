<?php 
  $class = "col-lg-$col mb-lg-auto mb-4 my-auto p-md-0 ms-auto";
  if(!empty($numOfPlans) && $numOfPlans >= 3 && $index == 1){
    $class = "col-lg-$col p-md-0 mb-lg-auto mb-4 z-index-2";
  }
  if(!empty($numOfPlans) && $numOfPlans >= 3 && $index == 2){
    $class = "col-lg-$col mb-lg-auto mb-4 my-auto p-md-0 me-auto";
  }

  $innerClass="";
  if(!empty($numOfPlans) && $numOfPlans >= 3 && ($index == 0)){
    $innerClass="border-radius-top-end-lg-0 border-radius-bottom-end-lg-0";
  }
  if(!empty($numOfPlans) && $numOfPlans >= 3 && ($index == 2)){
    $innerClass="border-radius-top-start-lg-0 border-radius-bottom-start-lg-0";
  }

   //$class = "col-lg-$col p-md-0 mb-lg-auto mb-4 z-index-2 ml-2";
?>
<div class="{{ $class }}">
  <div class="card  {{$innerClass}} ">
    <div class="card-header text-center pt-4 pb-3">
      <h6 class="display-6" class="text-dark opacity-8 mb-0">{{  __($plan['name']) }}</h6>
      <p class="card-text">{{ __($plan['description']) }}</p>
        <h1 class="font-weight-bolder">
          <small>@money($plan['price'], config('settings.cashier_currency'),config('settings.do_convertion'))</small>
          
        </h1>
    </div>
    <div class="card-body mx-auto pt-0">
      @foreach (explode(",",$plan['features']) as $feature)
      <div class="justify-content-left d-flex px-2 py-1">
        <div>
          <i class="fas fa-check text-dark opacity-6 text-sm"></i>
        </div>
        <div class="ps-2">
          <span class="text-sm">{{ __($feature) }}</span>
        </div>
      </div>
      @endforeach
    </div>
    
  </div>
</div>


       

      