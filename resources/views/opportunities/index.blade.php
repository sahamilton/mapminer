@extends('site.layouts.default')
@section('content')

<div class="container">
<h2>My Opportunities</h2>
   <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      <th>Date Opened</th>
      <th>Days Open</th>
      <th>Status</th>
      <th>Business</th>
      <th>Address</th>
      <th>Potential $$</th>
      <th>Potential Labor Reqts</th>
    </thead>
      <tbody>
        @foreach ($opportunities as $opportunity)
          <td>{{$opportunity->created_at ? $opportunity->created_at->format('Y-m-d') : ''}}</td>
          <td>{{$opportunity->daysOpen()}}</td>
          <td>{{$opportunity->closed}}</td>
          <td>{{$opportunity->address->businessname}}</td>
          <td>{{$opportunity->address->fullAddress()}}</td>
          <td>{{$opportunity->value}}</td>
          <td>{{$opportunity->requirements}}</td>
        @endforeach

      </tbody>
    <tfoot>
      
    </tfoot>

</table>
</div>
@include('partials._scripts')
@endsection