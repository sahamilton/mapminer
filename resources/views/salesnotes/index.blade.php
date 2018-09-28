@extends('admin/layouts/default')

{{-- Page content --}}
@section('content')
<h2>Manage Sales Notes</h2>
 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
  <thead>
    <th>Company</th>
    <th>Sales Notes</th>
    <th>Servicelines</th>
    <th>Action</th>
  </thead>
  <tbody>

@foreach ($companies as $company)

 @if($company->salesnotes->count()>0)
                <tr class='success'>
                 @else
               <tr class='danger'>
                @endif

<td> 
<a href="{{route('company.show',$company->id)}}">{{$company->companyname}}</a>
</td>

<td>  @if($company->salesnotes->count()>0)
        Yes
        @else
        No
        @endif
</td>
<td>
@foreach ($company->serviceline as $serviceline)
<li>{{$serviceline->ServiceLine}}</li>
@endforeach
</td>
<td>
  <div class="btn-group">
       @if($company->salesnotes->count()>0)
        <a class="dropdown-item" 
        href="{{route('salesnotes.edit',$company->id)}}"
        title=" Edit {{trim($company->companyname)}}'s Sales Notes">
      @else
        <a class="dropdown-item"
        href="{{route('salesnotes.create','company='.$company->id)}}"
        title=" Create {{trim($company->name)}}'s Sales Notes">
      @endif
      <button type="button" class="btn btn-success" >
      <i class="fa fa-pencil text-info" aria-hidden="true"></i>   
      </a>
  	          
  </div>
</td>
@endforeach
</tbody>
</table>

@include('partials/_scripts')
@endsection
        