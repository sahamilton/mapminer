@extends('site.layouts.default')
@section('content')

<div class="container">
<h2>My Teams Branch Opportunities</h2>
   <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      <th>Branch</th>
      <th>Manager</th>
      <th>Leads</th>
      <th>Activities</th>
      <th>Opportunities</th>
      
      <th>Closed</th>
    </thead>
      <tbody>
        @foreach ($data['branches'] as $branch)

        <tr>
          <td>
            <a href="{{route('opportunities.branch',$branch->id)}}">
              {{$branch->branchname}}
            </a>
          </td>
          
          <td>
            @foreach ($branch->manager as $manager)
              <li>{{$manager->fullName()}}</li>
            @endforeach
          </td>
          <td align="center">{{$branch->leads->count()}}</td>
           <td>
            @if(isset($data['stats'][$branch->id]))
              @foreach ($data['stats'][$branch->id] as $activity=>$count)
                <li>{{$activity}} = {{$count}}</li>
              @endforeach
            @endif
          </td>
          <td align="center">{{$branch->opportunities->count()}}</td>
          <td align="center">
            @if(isset($data['stats'][$branch->id]['Closed - Won']))
            {{$data['stats'][$branch->id]['Closed - Won']}}
            @endif
          </td>
        @endforeach

      </tbody>
    <tfoot>
      
    </tfoot>

</table>
</div>

@include('partials._scripts')
@endsection