<div class="modal fade" id="modalUpdateProfile" tabindex="-1" role="dialog" aria-labelledby="updateProfileModel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold">Update Profile</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mx-3">
        <form  
          action="{{route('update.profile')}}"
          method="POST"
        >
        @csrf
       
        <div class="md-form mb-4">
          <i class="fa-solid fa-location-dot prefix grey-text"></i>
          <input type="text" name="address" id="address" class="form-control" value="{{$user->person->fullAddress()}}">
          <label data-error="wrong" data-success="right" for="defaultForm-email">Your Address:</label>
        </div>

        <div class="md-form mb-4">
          <i class="fa-solid fa-phone-rotary prefix grey-text"></i>
          <input type="text" id="phone" name="phone" class="form-control" value="{{$user->person->phoneNumber}}">
          <label data-error="wrong" data-success="right" for="defaultForm-pass">Your Phone:</label>
        </div>

      </div>
        <div class="modal-footer d-flex justify-content-center">
          <button class="btn btn-sm btn-info">Update</button>
        </div> 
      </form>
    </div>
  </div>
</div>

<div class="text-center">
  <a href="" class="btn btn-default btn-rounded mb-4" data-toggle="modal" data-target="#modalLoginForm">Launch
    Modal Login Form</a>
</div>