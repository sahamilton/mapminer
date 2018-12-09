<div class="float-right">
	 <p><a href="{{ route('branches.index') }}">Show all branches</a></p>	
		</div>
        <h1>{{$data['title']}}</h1>

       <h4> within 10 miles of the {{ucwords(strtolower($data['branch']->branchname))}} branch # {{$data['branch']->id}} </h4>
        <h4>Address:</h4> 
        <p>{{$data['branch']->street}} {{$data['branch']->suite}}<br/>
        {{$data['branch']->city}},{{$data['branch']->state}} {{$data['branch']->zip}}<br />
        {{$data['branch']->phone}}</p>

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

                        <span type="button" class="far fa-copy btn-copy js-tooltip js-copy" data-toggle="tooltip" data-placement="bottom" data-copy="{{$people->postName()}}" title="Copy name to clipboard"></span> 
                    
                </strong>  
                @if($people->phone != "")
                   <i class="fas fa-phone" aria-hidden="true"></i>
                    {{$people->phone}} 
                @endif
                @if($people->has('userdetails'))
                    <i class="far fa-envelope" aria-hidden="true"></i>
                    <a href="mailto:{{$people->userdetails()->first()->email}}"
                        title="Email {{$people->firstname}}">
                    {{$people->userdetails()->first()->email}}</a> 
                    <span type="button" class="far fa-copy btn-copy js-tooltip js-copy" data-toggle="tooltip" data-placement="bottom" data-copy="{{$people->userdetails()->first()->email}}" title="Copy email to clipboard"></span> 

                    
                @endif
            </p>
        @endforeach
        <p>Branch service radius: {{$data['branch']->radius}} miles.</p>
        <h4>Service Lines:</h4>
        <ul style="margin-left:40px">
            @foreach($data['branch']->servicelines as $serviceline)
               <li>  {{$serviceline->ServiceLine}} </li>
            @endforeach
        </ul>            
<?php $data['address'] = $data['branch']->street ." ".$data['branch']->city ." ".$data['branch']->state;
$data['lat'] = $data['branch']->lat;
$data['lng'] = $data['branch']->lng;


?>

@if($type=='map')

<p><a href="{{route('showlist.locations',$data['branch']->id)}}"><i class="fas fa-th-list" aria-hidden="true"></i> List view</a></p>
@else
<p><a href="{{route('branches.show',$data['branch']->id)}}"><i class="far fa-flag" aria-hidden="true"></i>Map View</a></p>

@endif
<div>
@include('partials/advancedsearch')
</div>
@include('maps/partials/_form')


