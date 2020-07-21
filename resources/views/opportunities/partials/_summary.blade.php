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
              <a href="{{route('branchdashboard.show',$branch->id)}}">{{$branch->branchname}}</a>
            </td>
            
            <td>
              @foreach ($branch->manager as $manager)
                <li>{{$manager->fullName()}}</li>
              @endforeach
            </td>
             <td align="center">
              <a href="{{route('opportunities.branch',$branch->id)}}">
                {{$branch->open_opportunities}}
              </a>
            </td>
            <td align="center">
              @if($branch->won_opportunities >0)
              <a href="{{route('opportunities.branch',$branch->id)}}">
                {{$branch->won_opportunities}}
              </a> 
              @else
               0 
               @endif
            </td>
            <td  align="center"> 
              @if($branch->lost_opportunities >0)
              <a href="{{route('opportunities.branch',$branch->id)}}">
                {{$branch->lost_opportunities}}
              </a> 
            @else 
              0 
            @endif
          </td>

            <td align="center">
              <a href="{{route('lead.branch',$branch->id)}}"> 
                {{$branch->leads_count}}
              </a>
            </td>
            
           
            <td align="center">
              <a href="{{route('activity.branch',$branch->id)}}">
                 {{$branch->activities_count}}
              </a>
            </td>
         
        </tr>
       @endforeach
  </tbody>
</table>