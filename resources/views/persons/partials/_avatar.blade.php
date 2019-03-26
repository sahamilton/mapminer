<div class="col-sm-5 float-right profile">
<div class="profile-avatar-wrap">
  <form method="post" action="{{route('change.avatar')}}" id="{{$user->person->id}}" enctype="multipart/form-data" >
    @csrf


      <img src=" {{asset('/storage/avatars/'.$user->person->avatar)}}" 
        id="profile-avatar"  
        style="border-radius:50%;"
        width=150px;
        height= 150px;/>
      <input type="file" 
        name="avatar" 
        class="btn btn-sm"/>
      <input type="submit" 
        name="submit" 
        value="Change Avatar" 
        class="btn btn-sm btn-success" />
     
    
    <input type="hidden" name="person_id" value="{{$user->person->id}}" />
   
  </form>
</div>
</div>