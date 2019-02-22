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
      @foreach ($activityTypes as $key=>$type)
     @if($key < 7)
      <th>{{$type->activity}}</th>
      @endif
       @endforeach
      <th>Total Activities</th>
    <th>Won</th>
    <th>Lost</th>
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
          <td align="center">{{$branch->leads_count}}</td>
          
          <td align="center">{{$branch->opportunities_count}}</td>
          
            @foreach ($activityTypes as $key=>$type)
             @if($key < 7)
              @if(array_key_exists($branch->id, $data['activities']) && isset($data['activities'][$branch->id][$type->id]))
                <td align='center'><a href="{{route('branch.activity',['branch'=>$branch->id,'activity'=>$type->id])}}"
                  title= "Show this periods {{$type->activity}} activities of the {{$branch->branchname}} branch">{{$data['activities'][$branch->id][$type->id]}}</td>
                @else
                <td align="center">0</td>
              @endif
              @endif
           @endforeach

           
          </td>
          <td>{{$branch->activities_count}}</td>
          <td align="center">
            @if($branch->won >0)<a href="{{route('opportunities.branch',$branch->id)}}">{{$branch->won}}</a> @else 0 @endif
          </td>
          <td  align="center"> @if($branch->lost >0)<a href="{{route('opportunities.branch',$branch->id)}}">{{$branch->lost}}</a> @else 0 @endif</td>
        @endforeach

      </tbody>
    <tfoot>
      
    </tfoot>

</table>
</div>

@include('partials._scripts')
@endsection