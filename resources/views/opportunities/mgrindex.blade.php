@extends('site.layouts.default')
@section('content')

<div class="container">
<h2>My Teams Branch Opportunities</h2>
   <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      <th>Branch</th>
      <th>Manager</th>
      <th>Opportunities</th>
    </thead>
      <tbody>
        @foreach ($branches as $branch)
        <tr>
          <td>{{$branch->branchname}}</td>
          <td>{{$branch->manager->first() ? $branch->manager->first()->fullName() : ''}}</td>
          <td>{{$branch->opportunities->count()}}</td>
          
        @endforeach

      </tbody>
    <tfoot>
      
    </tfoot>

</table>
</div>
@include('activities.partials._activities')
@include('partials._scripts')
@endsection