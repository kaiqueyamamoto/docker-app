<!-- Modal -->
<div class="modal fade" id="modal-register" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __('poslanding.modal_title') }}</h5>
      </div>
      <div class="modal-body">
        <form action="{{ route('newrestaurant.register') }}" class="d-flex flex-column mb-5 mb-lg-0">
          <input class="form-control" type="text" name="name" placeholder="{{ __('poslanding.modal_input_name')}}" required>
          <input class="form-control my-3" type="email" name="email" placeholder="{{ __('poslanding.modal_input_email')}}" required>
          <input class="form-control my-1" type="text" name="phone" placeholder="{{ __('poslanding.modal_input_phone')}}" required>
          
          <button disabled class="btn bg-gradient-primary my-3" id="submitRegister" type="submit">{{ __('poslanding.join_now')}}</button>

          <div class="form-check"><input type="checkbox" name="termsCheckBox" id="termsCheckBox" class="form-check-input"> 
            <label for="terms" class="form-check-label">
              &nbsp;&nbsp;{{__('poslanding.i_agree_to')}}
              <a href="{{config('settings.link_to_ts')}}" target="_blank" style="text-decoration: underline;">{{__('poslanding.terms_of_service')}}</a> {{__('poslanding.and')}}
              <a href="{{config('settings.link_to_pr')}}" target="_blank" style="text-decoration: underline;">{{__('poslanding.privacy_policy')}}</a>.
            </label>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Post register -->
<div class="modal fade " id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
  <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="py-3 text-center">
          <i class="ni ni-bell-55 ni-3x"></i>
          <h4 class="text-gradient text-danger mt-4">{{ session('status') }}</h4>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-link  ml-auto" data-bs-dismiss="modal">{{ __('close') }}</button>
      </div>
    </div>
  </div>
</div>