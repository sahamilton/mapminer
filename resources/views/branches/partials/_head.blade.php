<div class="pull-right">
	 <p><a href="{{ route('branches.index') }}">Show all branches</a></p>	
		</div>
        <h1>{{$data['title']}}</h1>

       <h4> within 10 miles of the {{ucwords(strtolower($data['branch']->branchname))}} branch # {{$data['branch']->id}} </h4>
        <h4>Address:</h4> 
        <p>{{$data['branch']->address->street}} {{$data['branch']->address->suite}}<br/>
        {{$data['branch']->address->city}},{{$data['branch']->state}} {{$data['branch']->address->zip}}<br />
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
                        {{$people->postName()}}  
                    </a> 
                </strong>  
                @if($people->phone != "")
                   <i class="fa fa-phone" aria-hidden="true"></i>
                    {{$people->phone}} 
                @endif
                @if($people->has('userdetails'))
                    <i class="fa fa-envelope" aria-hidden="true"></i>
                    <a href="mailto:{{$people->userdetails()->first()->email}}"
                        title="Email {{$people->firstname}}">
                    {{$people->userdetails()->first()->email}}
                    </a> 
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
<p><a href="{{route('showlist.locations',$data['branch']->id)}}"><i class="fa fa-th-list" aria-hidden="true"></i> List view</a></p>
@else
<p><a href="{{route('branches.show',$data['branch']->id)}}"><i class="fa fa-flag" aria-hidden="true"></i>Map View</a></p>
@endif
<div>
@include('partials/advancedsearch')
</div>
@include('maps/partials/_form')


