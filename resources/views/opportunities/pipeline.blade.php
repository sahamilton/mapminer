
@extends('site.layouts.default')
@section('content')
<div class="container">
@include('companies.partials._searchbar')
@php $statuses = ['open','closed - won','closed - lost']; @endphp
@include('maps.partials._form')
<h2>Pipeline Opportunities</h2>
<p><a href="{{route('dashboard.show', session('branch'))}}">Return To Branch Dashboard</a></p>

@if(count($myBranches)>1)

<div class="col-sm-4">
<form name="selectbranch" method="post" action="{{route('opportunity.branch')}}" >
@csrf

 <select class="form-control input-sm" id="branchselect" name="branch" onchange="this.form.submit()">
  @foreach ($myBranches as $key=>$branch)
    <option value="{{$key}}">{{$branch}}</option>
  @endforeach 
</select>

</form>
</div>
@endif
<div class="row">
 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      <th>Title</th>
      <th>Date Opened</th>
      <th>Days Open</th>
      <th>Status</th>
      <th>Company</th>
      <th>Address</th>
     
      <th>Potential Headcount</th>
      <th>Potential Duration (mos)</th>
      <th>Potential $$</th>
      <th>Expected Close</th>
      <th>Last Activity</th>
      
    </thead>
      <tbody>
        @foreach ($pipeline as $opportunity)
     
      
        <tr>
          <td>
           
            <a href="{{route('opportunity.show',$opportunity->id)}}" title="Review, edit or delete this opportunity">
            {{$opportunity->title ?  $opportunity->title : $opportunity->id}} <i class="fas fa-edit class="text text-info"></i></a>

          </td>
          <td>{{$opportunity->created_at ? $opportunity->created_at->format('Y-m-d') : ''}}
          </td>
          <td>{{$opportunity->daysOpen()}}</td>
          <td>

            {{$statuses[$opportunity->closed]}}
            
          </td>
          <td>
          
            <a href= "{{route('address.show',$opportunity->address->address->id)}}">
              {{$opportunity->address->address->businessname}}
            </a>
          </td>
          <td>{{$opportunity->address->address->fullAddress()}}</td>
          
          <td  class="text-right">{{$opportunity->requirements}}</td>
          <td  class="text-right">{{$opportunity->duration}}</td>
          <td class="text-right">${{number_format($opportunity->value,2)}}</td>
          <td>
            @if($opportunity->expected_close )
            {{$opportunity->expected_close->format('Y-m-d')}}
            @endif
          </td>
          <td>
            @if($opportunity->address->activities->count() >0 )

              {{$opportunity->address->activities->last()->activity_id}}
             <br />
            {{$opportunity->address->activities->last()->activity_date->format('Y-m-d')}}
            @endif
          </td>
          
         
        </tr>
        @endforeach

      </tbody>
    <tfoot>
      <th colspan='6'>
      <td class="text-right">{{number_format($pipeline->sum('requirements'),2)}}</td>
      <td class="text-right">{{number_format($pipeline->avg('duration'),2)}}</td>
      
      <td class="text-right">${{number_format($pipeline->sum('value'),2)}}</td>
      </th>
    </tfoot>

</table>






</div>

@include('partials._scripts')
</div>
@endsection