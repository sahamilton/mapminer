@extends('site.layouts.default')
@section('content')

<h1>{{$branch->branchname . " leads"}}</h1>
<p><a href="{{route('dashboard.show', $branch->id)}}">Return To Branch Dashboard</a></p>

@if(count($myBranches) > 1)
    <div class="col-sm-4">
        <form name="selectbranch" method="post" action="{{route('leads.branch')}}" >
        @csrf
            <select 
                class="form-control input-sm" 
                id="branchselect" 
                name="branch" 
                onchange="this.form.submit()">
                @foreach ($myBranches as $key=>$branchname)
                    <option {{$branch->id == $key ? 'selected' : ''}} value="{{$key}}">{{$branchname}}</option>
                @endforeach 
            </select>

        </form>
    </div>
@endif
   
<div class="row float-right">
        <button type="button" 
            class="btn btn-info float-right" 
            data-toggle="modal" 
            data-target="#add_lead">
              Add Lead
        </button>
    </div>



@include('branchleads.partials._mylead') 


 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        <th>Company</th>
        <th>Date Added</th>
        <th>Address</th>
        <th>Lead Source</th>
        <th>Current Campaign</th>
        <th>Last Activity</th>
        <th>Remove</th>
    </thead>
    <tbody>

    @foreach($branch->leads as $lead)

    <tr>
        <td>
            <a href="{{route('address.show',$lead->id)}}">
                {{$lead->businessname}}
            </a>
        </td>
        <td>
           @if($lead->assignedToBranch->where('id','=',$branch->id)->first()) 
            {{$lead->assignedToBranch
                ->where('id','=',$branch->id)
                ->first()
                ->pivot
                ->created_at->format("Y-m-d")}}
            
            @endif
       
        </td>
        <td>{{$lead->fullAddress()}}</td>
        <td>
            @if($lead->leadsource)
             {{$lead->leadsource->source}}
            @endif
        </td>
        <td>
            @foreach ($lead->currentcampaigns as $campaign)
               
                   <li>{{$campaign->title}}</li>
               
            @endforeach
            @if ($lead->currentcampaigns->count() ==0 )
            <a 
            data-pk="{{$lead->id}}"
            data-id="{{$lead->id}}"
            data-toggle="modal" 
            data-target="#addtocampaign" 
            data-title = "" 
            href="#">
                <i class="text-success fas fa-plus-circle"></i> Add to current campaign
            </a>
           @endif
        </td>
        <td>
            @if($lead->lastActivity)
                {{$lead->lastActivity->activity_date->format('Y-m-d')}}        
            @endif
        </td>
        <td>
           
          <a 
            data-href="{{route('branch.lead.remove',$lead->id)}}" 
            data-toggle="modal" 
            data-target="#confirm-remove" 
            data-title = " this lead and all associated opportunities from your branch" 
            href="#">
                <i class="fas fa-trash-alt text-danger"></i>
            </a>
           
        </td>




    </tr>
   @endforeach

    </tbody>
</table>
 
@include('branchleads.partials._branchleadmodal')
@include('branchleads.partials._branchcampaignmodal')
@include('partials._scripts')
@endsection
