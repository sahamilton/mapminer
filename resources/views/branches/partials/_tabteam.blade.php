<h4>Branch Team</h4>
        @foreach ($data['branch']->relatedPeople()->get() as $people)

            <p>
                <strong>
                    @if($people->pivot->role_id)
                        {{$roles[$people->pivot->role_id]}}:
                    @endif
                    <a href="{{route('person.show',$people->id)}}"
                        title = "See {{$people->firstname}}'s organizational details">
                        {{$people->postName()}} </a> 

                       
                    
                </strong>  
                @if($people->phone != "")
                   <i class="fas fa-phone" aria-hidden="true"></i>
                    {{$people->phone}} 
                @if($people->has('userdetails'))
                    <i class="far fa-envelope" aria-hidden="true"></i>
                    <a href="mailto:{{$people->userdetails()->first()->email}}"
                        title="Email {{$people->firstname}}">
                    {{$people->userdetails()->first()->email}}</a> 
                 @endif  

                    
                @endif
            </p>
        @endforeach