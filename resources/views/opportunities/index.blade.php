@extends('site.layouts.default')
@section('content')

<div class="container">
<h2>{{$opportunities->first()->branch->branchname}} Branch Opportunities</h2>
   <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      <th>Date Opened</th>
      <th>Days Open</th>
      <th>Status</th>
      <th>Business</th>
      <th>Address</th>
      <th>Potential $$</th>
      <th>Potential Labor Reqts</th>
      <th>Last Activity</th>
      <th>Activities</th>
    </thead>
      <tbody>
        @foreach ($opportunities as $opportunity)
        
          <td>{{$opportunity->created_at ? $opportunity->created_at->format('Y-m-d') : ''}}</td>
          <td>{{$opportunity->daysOpen()}}</td>
          <td>{{$opportunity->closed}}</td>
          <td>
            <a href= "{{route('opportunity.show',$opportunity->id)}}">
              {{$opportunity->address->businessname}}
            </a>
          </td>
          <td>{{$opportunity->address->fullAddress()}}</td>
          <td>{{$opportunity->value}}</td>
          <td>{{$opportunity->requirements}}</td>
          <td>
            {{$opportunity->activities ? $activityTypes[$opportunity->activities->last()->activity] : ''}}<br />
            {{$opportunity->activities ? $opportunity->activities->last()->activity_date->format('Y-m-d') : ''}}
          </td>
          <td>
              <a 
                        data-href="{{route('activity.store')}}" 
                        data-toggle="modal" 
                        data-pk = "{{$opportunity->id}}"
                        data-id="{{$opportunity->id}}"
                        data-target="#add-activity" 
                        data-title = "location" 
                        href="#">
                    <i class="fa fa-plus-circle text-success" aria-hidden="true"></i> Add Activity</a>
                    </div></div>

          </td>
        @endforeach

      </tbody>
    <tfoot>
      
    </tfoot>

</table>
</div>
@include('activities.partials._activities')
@include('partials._scripts')
@endsection