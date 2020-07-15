<h4>Branch Team</h4>

        @foreach ($data['branch']->manager as $people)

            <p>
                <strong>
                    {{$people->business_title}}
                    <a href="{{route('person.show',$people->id)}}"
                        title = "See {{$people->firstname}}'s organizational details">
                        {{$people->postName()}} </a> 

                       
                    
                </strong>  
                @if($people->phone != "")
                   <i class="fas fa-phone" aria-hidden="true"></i>
                    {{$people->phone}} 
                    
                        <i class="far fa-envelope" aria-hidden="true"></i>
                        <a href="mailto:{{$people->userdetails->email}}"
                            title="Email {{$people->firstname}}">
                        {{$people->userdetails->email}}</a> 
                   

                    
                @endif
            </p>
          
           
            <p>
                <strong>
                    {{$people->reportsTo->business_title}}
                    <a href="{{route('person.show',$people->reportsTo->id)}}"
                        title = "See {{$people->reportsTo->firstname}}'s organizational details">
                        {{$people->reportsTo->postName()}} </a> 

                       
                    
                </strong>  
                    @if($people->reportsTo->phone !='')
                        <i class="fas fa-phone" aria-hidden="true"></i>
                        {{$people->reportsTo->phone}} 
                   @endif 
                        <i class="far fa-envelope" aria-hidden="true"></i>
                        <a href="mailto:{{$people->reportsTo->userdetails->email}}"
                            title="Email {{$people->reportsTo->firstname}}">
                        {{$people->reportsTo->userdetails->email}}</a> 
              
                
             </p>
          
        @endforeach
        <h4>Servicelines</h4>
        @foreach($data['branch']->servicelines as $serviceline)
            <li>{{$serviceline->ServiceLine}}</li>

        @endforeach