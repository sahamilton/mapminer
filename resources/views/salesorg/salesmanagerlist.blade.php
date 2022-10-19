@extends('site.layouts.default')
@section('content')

<h2> {{$salesperson->fullName()}}'s Sales Team</h2>



  @foreach ($salesperson->userdetails->roles as $role)
    <h4> {{$role->display_name}}</h4>
  @endforeach


    <p><i class="far fa-envelope" aria-hidden="true"></i> 

    <a href="mailto:{{$salesperson->userdetails->email}}" title="Email {{$salesperson->fullName()}}">{{$salesperson->userdetails->email}}</a> </p>

@if ($salesperson->reportsTo->userdetails)
<p>Reports to: <a href = "{{route('salesorg.show',$salesperson->reportsTo->id)}}" 
title= "See {{$salesperson->reportsTo->fullName()}}'s sales team"> {{$salesperson->reportsTo->fullName()}}  {{$salesperson->reportsTo->userdetails->roles->count() !=0 ? ' - ' . $salesperson->reportsTo->userdetails->roles->first()->name : ''}}</a>
@endif




  <p><a href="{{route('salesorg.show',array($salesperson->id, 'view'=>'map'))}}"

  title="See map view of {{$salesperson->fullName()}}'s sales team"><i class="far fa-flag" aria-hidden="true"></i> Map View</a></p>    

@include('leads.partials.search')
<table id ='nosorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     
    <th> Name </th> 
    <th> Role </th>
    <th> Reports to </th>
    <th>City</th>
    <th>State</th>
    <th>Verticals</th>
   
   
    </thead>
    <tbody>
  

   @foreach($salesperson->directReports as $reports)



    <tr>  

    <td>
      @if (auth()->user()->canImpersonate() && $reports->userdetails->canBeImpersonated())
        <a href="{{route('impersonate', $reports->user_id)}}" title="Login as {{$reports->fullName()}}">
            <i class=" text-danger fa-duotone fa-key"></i>
        </a>
        @endif
    {!!str_repeat ( '&nbsp;' , ($reports->depth - $salesperson->depth) * 3 )!!} 
        @if($reports->isLeaf())
        <a href="{{route('salesorg.show',$reports->id)}}"
        title="See {{$reports->fullName()}}'s sales area">
        {{$reports->fullName()}}
        </a>
        @else
        <a href="{{route('salesorg.show',$reports->id)}}"
        title="See {{$reports->fullName()}}'s sales team">
        {{$reports->fullName()}}
        </a>
        @endif
        
       
   </td>

   <td>
       @if(isset($reports->userdetails->roles)) 
         @foreach($reports->userdetails->roles as $role)
            {{$role->display_name}}<br />
        @endforeach
        @endif
        
    </td>

   <td>
    @if(isset($reports->reportsTo))

      {{$reports->reportsTo->firstname}} {{$reports->reportsTo->lastname}}
    @endif
   
   @if($reports->isLeaf())
     <td> {{$reports->city}}</td><td>{{$reports->state}}</td>
    @else
    <td></td><td></td>  
   @endif
  
   <td>
   <ul>
     @foreach ($reports->industryfocus as $vertical)
       <li>
         <a href="{{route('person.vertical',$vertical->id)}}"
         title="See all {{$vertical->filter}} industry sales team">
          {{$vertical->filter}}
         </a>
       </li>
     @endforeach
     </ul>
   </td>
    
   
    </tr>

   @endforeach
    
    </tbody>
    </table>





@include('partials._scripts')
@endsection
