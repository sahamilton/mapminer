@extends('site.layouts.default')
@section('content')

<h1>{{$title}}</h1>
<p><a href="{{route('dashboard.index')}}">Return To Branch Dashboard</a></p>
@if(count($myBranches)>1)

<div class="col-sm-4">
<form name="selectbranch" method="post" action="{{route('leads.branch')}}" >
@csrf

 <select class="form-control input-sm" id="branchselect" name="branch" onchange="this.form.submit()">
  @foreach ($myBranches as $key=>$branch)
    <option {{$data['branches']->first()->id == $key ? 'selected' : ''}} value="{{$key}}">{{$branch}}</option>
  @endforeach 
</select>

</form>
</div>
@endif
 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Company</th>
    <th>Company Name</th>
    <th>Address</th>
    <th>Distance</th>

    </thead>
    <tbody>

        @foreach($data['leads'] as $lead)

    <tr>
        <td>
            <a href="{{route('myleads.show',$lead->id)}}">
                {{ $lead->companyname != '' ? $lead->companyname: $lead->businessname}} 
            </a>
        </td>
        <td>{{$lead->businessname}}</td>
        <td>{{$lead->fullAddress()}}</td>
        <td>distance</td>



    </tr>
   @endforeach

    </tbody>
    </table>
   


  
@include('partials._scripts')
@endsection
