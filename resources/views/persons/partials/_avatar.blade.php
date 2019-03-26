<style>

.profile {
  width: 40%;
}
.profile-avatar-wrap {
  width: 50%;
  float: left;
  margin: 0 20px 5px 0;
  position: relative;

  border: 5px solid transparent;
}

.profile-avatar-wrap img {
  width: 100%;
  display: block;
}
</style>
{{$user->person->avatar}}
<div class="float-right profile">
<div class="profile-avatar-wrap">
  <form method="post" action="{{route('change.avatar')}}" id="{{$user->person->id}}" enctype="multipart/form-data" >
    @csrf

    @if(! empty($person->avatar))

      <img src=" {{asset('/storage/avatars/'.$user->person->avatar)}}" 
        id="profile-avatar"  
        style="border-radius:50%;"/>
      <input type="file" 
        name="avatar" 
        class="btn btn-sm"/>
      <input type="submit" 
        name="submit" 
        value="Change Image" 
        class="btn btn-sm btn-success" />
     
    @else
      <img src="{{asset('/storage/avatars/avatar.png')}}" 
      id="profile-avatar"  
      style="border-radius:50%;"/>
     <input type="file" 
       name="avatar" 
       class="btn btn-sm"/>
     <input type="submit" 
       name="submit" 
       value="Add Image" 
       class="btn btn-sm btn-success" />
    @endif
    
    <input type="hidden" name="person_id" value="{{$user->person->id}}" />
   
  </form>
</div>
</div>