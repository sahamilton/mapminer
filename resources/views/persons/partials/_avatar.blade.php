<div class="profile-avatar-wrap">
  <form method="post" action="{{route('change.avatar')}}" id="{{$person->id}}" enctype="multipart/form-data" >
    @csrf

    @if(! empty($person->avatar))

      <img src=" {{asset('/storage/avatars/'.$person->avatar)}}" 
        id="profile-avatar{{$loop->iteration}}"  
        style="border-radius:50%;"/>
      <input type="file" 
        name="avatar" 
        class="btn btn-sm"/>
      <input type="submit" 
        name="submit{{$person->id}}" 
        value="Change Image" 
        class="btn btn-sm btn-success" />
     
    @else
      <img src="{{asset('/storage/avatars/avatar.png')}}" 
      id="profile-avatar{{$loop->iteration}}"  
      style="border-radius:50%;"/>
     <input type="file" 
       name="avatar" 
       class="btn btn-sm"/>
     <input type="submit" 
       name="submit{{$person->id}}" 
       value="Add Image" 
       class="btn btn-sm btn-success" />
    @endif
    
    <input type="hidden" name="person_id" value="{{$person->id}}" />
   
  </form>
</div>