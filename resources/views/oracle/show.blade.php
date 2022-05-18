@extends('admin.layouts.default')
@section('content')

<h2>Oracle HR Data</h2>
<p><a href="{{route('oracle.index')}}">Return to Oracle</a></p>

<table class ='table table-bordered table-striped table-hover'>
   <thead>
      
         <th>Oracle Data:  {{$oracle->fullName()}}</th>
         <th>
            @if($oracle->mapminerUser)
               <a href="{{route('person.details', $oracle->mapminerUser->person->id)}}">See Mapminer Profile</a>
               <i class="far fa-check-circle text-success" title="Mapminer user"></i>
               @else
               Not In Mapminer
                  @if($oracle->oracleManager->mapminerUser)
                  <p>
                     <a href="{{route('oracle.useradd', $oracle->id)}}" class="btn btn-success">Add to Mapminer</a>
                  </p>
                  @endif
               @endif
            </th>
   </thead>
   <tbody>
      <tr><td>Employee ID</td><td>{{$oracle->person_number}}</td></tr>
      <tr><td>Email</td><td>{{$oracle->primary_email}}</td></tr>
      <tr><td>Business Title</td><td>{{$oracle->business_title}}</td></tr>
      <tr><td>Job Profile</td><td>{{$oracle->job_profile}}</td></tr>
      <tr><td>Management Level</td><td>{{$oracle->management_level}}</td></tr>
      <tr><td>Hire Date</td><td>{{$oracle->current_hire_date}}</td></tr>
      <tr><td>ZipCode</td><td>{{$oracle->home_zip_code}}</td></tr>
      <tr><td>Location</td><td>{{$oracle->location_name}}</td></tr>
      <tr><td>Country</td><td>{{$oracle->country}}</td></tr>
      <tr><td>Service Line</td><td>{{$oracle->service_line}}</td></tr>
      <tr><td>Manager</td>
         <td>
            @if($oracle->oracleManager)
               @if(isset($oracle->oracleManager->mapminerUser))
               <a href="{{route('user.show', $oracle->oracleManager->mapminerUser->id)}}">
                  {{$oracle->oracleManager->mapminerUser->person->fullName()}}
               </a>
                <i class="far fa-check-circle text-success" title="Mapminer user"></i>
               @else
                  Not In Mapminer:
                  <a href="{{route('oracle.show', $oracle->oracleManager->id)}}">{{$oracle->oracleManager->fullName()}}</a>
               @endif
            @endif
         </td>
      </tr>
      <tr><td>Manager Email</td><td>{{$oracle->manager_email_address}}</td></tr>
      <tr><td>Last Update</td><td>{{$oracle->created_at->format('Y-m-d')}}</td></tr>
      
   </tbody>
</table>  

<table class ='table table-bordered table-striped table-hover'>
   <thead>
      
         <th colspan="2">Team Members</th>
   </thead>
   <tbody>
     


@foreach ($oracle->teamMembers as $team)
 <tr>
   <td>
      @if($team->mapminerUser)
         <i class="far fa-check-circle text-success" title="Mapminer user"></i>
         <a href="{{route('person.details', $team->mapminerUser->person->id)}}">
            {{$team->fullName()}}
         </a>
      
      
         
      @else
         <i class="far fa-times-circle text-danger" title="Not a Mapminer user"></i>
         <a href="{{route('oracle.show', $team->id)}}">{{$team->fullName()}}</a>
         @if($team->oracleManager->mapminerUser)
      
               <a href="{{route('oracle.useradd', $team->id)}}" title="Add {{$team->fullName()}} to Mapminer" >
                  <i class="text-success fa-solid fa-user-plus"></i></a>
   
         @endif
          
      @endif
   </td>
   <td>
      {{$team->job_profile}}</td>
      <td> {{$team->location_name}}</td>
   </tr>
@endforeach
</tbody>
</table>

@endsection
