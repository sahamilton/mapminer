@extends('site.layouts.default')
@section('content')

<div class="container">
<h2>My Teams Branch Opportunities</h2>
   <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      <th>Branch</th>
      <th>Manager</th>
      <th>Leads</th>
      <th>Opportunities</th>
     
      <th class="tip" title="Activities in the past month">Total Activities<span class="text text-warning">*</span></th>
    <th>Won</th>
    <th>Lost</th>
    </thead>
      <tbody>

        @foreach ($data as $branch)
       

        <tr>
          <td>
           
              {{$branch->branchname}}
           
          </td>
          
          <td>
            @foreach ($branch->manager as $manager)
              <li>{{$manager->fullName()}}</li>
            @endforeach
          </td>
          <td align="center"><a href="{{route('lead.branch',$branch->id)}}">{{$branch->leads_count}}</td>
          
          <td align="center"><a href="{{route('opportunities.branch',$branch->id)}}">{{$branch->opportunities_count}}</a></td>
          
            
          <td align="center"><a href="{{route('activity.branch',$branch->id)}}">{{$branch->activities->count()}}</a></td>
          <td align="center">
            @if($branch->won >0)<a href="{{route('opportunities.branch',$branch->id)}}">{{$branch->won}}</a> @else 0 @endif
          </td>
          <td  align="center"> @if($branch->lost >0)<a href="{{route('opportunities.branch',$branch->id)}}">{{$branch->lost}}</a> @else 0 @endif</td>
        @endforeach

      </tbody>
    <tfoot>
      <span class="text text-danger">*</span>In past month
    </tfoot>

</table>
</div>

@include('partials._scripts')
@endsection