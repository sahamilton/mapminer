<table 
id ='sorttable' 
class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      <th>Branch</th>
      <th>Manager</th>
      
      <th>Open Opportunities</th>
      <th>Won</th>
      <th>Lost</th>
      <th>Leads</th>
      <th class="tip" title="Activities in this period">
        Period Activities
      </th>
      
    </thead>
      <tbody>
        @foreach ($data['branches'] as $branch)
  
          <tr>
            <td>
              <a href="{{route('branchdashboard.show',$branch->id)}}"
                title="See {{$branch->branchname}}  dashboard">
                {{$branch->branchname}}
              </a>
            </td>
            
            <td>
              @foreach ($branch->manager as $manager)
                <li>{{$manager->fullName()}}</li>
              @endforeach
            </td>
             <td align="center">
              <a href="{{route('opportunities.branch',$branch->id)}}"
                 title="See {{$branch->branchname}}  opportunities">
                {{$branch->open_opportunities}}
              </a>
            </td>
            <td align="center">
              @if($branch->won_opportunities >0)
              <a href="{{route('opportunities.branch',$branch->id)}}"
                 title="See {{$branch->branchname}}  opportunities">
                {{$branch->won_opportunities}}
              </a> 
              @else
               0 
               @endif
            </td>
            <td  align="center"> 
              @if($branch->lost_opportunities >0)
              <a href="{{route('opportunities.branch',$branch->id)}}"
                 title="See {{$branch->branchname}}  opportunities">
                {{$branch->lost_opportunities}}
              </a> 
            @else 
              0 
            @endif
          </td>

            <td align="center">
              <a href="{{route('lead.branch',$branch->id)}}"
                 title="See {{$branch->branchname}}  leads"> 
                {{$branch->leads_count}}
              </a>
            </td>
            
           
            <td align="center">
              <a href="{{route('activity.branch',$branch->id)}}"
                 title="See {{$branch->branchname}}  activities">
                 {{$branch->activities_count}}
              </a>
            </td>
         
        </tr>
       @endforeach
  </tbody>
  <tfoot>
    <th colspan=2></th>
    <td align="center">{{$data['branches']->sum('open_opportunities')}}</td>
    <td align="center">{{$data['branches']->sum('won_opportunities')}}</td>
    <td align="center">{{$data['branches']->sum('lost_opportunities')}}</td>
    <td align="center">{{$data['branches']->sum('lead_count')}}</td>
    <td align="center">{{$data['branches']->sum('activities_count')}}</td>
  </tfoot>
</table>