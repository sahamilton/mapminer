@extends('site.layouts.maps')
@section('content')

  <h2>{{$salesteam->fullName()}}'s Team</h2>

  @foreach ($salesteam->userdetails->roles as $role)
    {{$role->display_name}}
  @endforeach
  @if(isset($salesteam->usersdetails->roles))
  <h3>
  @foreach ( $salesteam->usersdetails->roles as $role)
  {{$role->display_name}}
  @endforeach
  </h3>

  @endif

  @if($salesteam->reportsTo)
  <h4>Reports to:<a href="{{route('salesorg.show',$salesteam->reportsTo->id)}}" 
  title="See {{$salesteam->reportsTo->firstname}} {{$salesteam->reportsTo->lastname}}'s sales team">
    
    </a> 
  @endif
{{$salesteam->reportsTo->fullName()}}
@if(isset ($salesteam->reportsTo->userdetails->roles) && $salesteam->reportsTo->userdetails->roles->count()>0) 
    - {{$salesteam->reportsTo->userdetails->roles[0]->display_name}}
  @endif

  </h4>

  @if(isset ($salesteam->userdetails) && $salesteam->userdetails->email != '')

  <p><i class="far fa-envelope" aria-hidden="true"></i> <a href="mailto:{{$salesteam->userdetails->email}}" title="Email {{$salesteam->firstname}} {{$salesteam->lastname}}">{{$salesteam->userdetails->email}}</a> </p>
  @endif  
  <p><a href="{{route('salesorg.show',array($salesteam->id,'view'=>'list'))}}"
  title="See list view of {{$salesteam->fullName}}'s sales team">
  <i class="fas fa-th-list" aria-hidden="true"></i> List view</a></p>

      <div id="map-container">
        <div style="float:left;width:300px">
  <h2>Direct Reports:</h2>

  @foreach($salesteam->directReports as $reports)
    @if(isset($reports->userdetails))
      @if($reports->isLeaf())
      <a href="{{route('salesorg.show',$reports->id)}}"
        title="See {{$reports->firstname}} {{$reports->lastname}}'s sales area">
          {{$reports->firstname}} {{$reports->lastname}}</a> 
      @else
        <a href="{{route('salesorg.show',$reports->id)}}"
        title="See {{$reports->firstname}} {{$reports->lastname}}'s Sales Team">
          {{$reports->firstname}} {{$reports->lastname}}</a>  
      @endif
     
      @if($reports->userdetails->roles->count()>0)
        - {{$reports->userdetails->roles[0]->display_name}}
      @endif
      <br/>

    @endif
  @endforeach

  </div>
  <div class="container" style="float:right;width:700px;">
    @php  $data['type'] ='people'; @endphp
  @include('leads.partials.search')
<p>Branches = <img src='//maps.google.com/mapfiles/ms/icons/blue-dot.png' />
Sales Team  = <img src='//maps.google.com/mapfiles/ms/icons/red-dot.png' /></p>
    <div id="map" style="border:solid 1px red;margin-bottom:40px;"></div> 
  </div> 
</div>

   
    @endsection

