<!-- Modal -->
<div class="modal fade" id="modal-register" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ __('whatsapp.modal_title') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('newrestaurant.register') }}" class="d-flex flex-column mb-5 mb-lg-0">
          <input class="form-control" type="text" name="name" placeholder="{{ __('whatsapp.modal_input_name')}}" required>
          <input class="form-control my-3" type="email" name="email" placeholder="{{ __('whatsapp.modal_input_email')}}" required>
          <input class="form-control my-1" type="text" name="phone" placeholder="{{ __('whatsapp.modal_input_phone')}}" required>
          <button disabled class="btn btn-success my-3" id="submitRegister" type="submit">{{ __('whatsapp.join_now')}}</button>

          <div class="form-check"><input type="checkbox" name="termsCheckBox" id="termsCheckBox" class="form-check-input"> 
            <label for="terms" class="form-check-label">
              &nbsp;&nbsp;{{__('whatsapp.i_agree_to')}}
              <a href="{{config('settings.link_to_ts')}}" target="_blank" style="text-decoration: underline;">{{__('whatsapp.terms_of_service')}}</a> {{__('whatsapp.and')}}
              <a href="{{config('settings.link_to_pr')}}" target="_blank" style="text-decoration: underline;">{{__('whatsapp.privacy_policy')}}</a>.
            </label>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>