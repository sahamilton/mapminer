<table 
id ='sorttable' 
class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      <th>Branch</th>
      <th>Manager</th>
      <th>Leads</th>
      <th>Open Opportunities</th>
      <th class="tip" title="Activities in this period">
        Period Activities
      </th>
      <th>Won</th>
      <th>Lost</th>
    </thead>
      <tbody>
        @foreach ($data['branches'] as $branch)
      
          <tr>
            <td>
              <a href="{{route('dashboard.show',$branch->id)}}">{{$branch->branchname}}</a>
            </td>
            
            <td>
              @foreach ($branch->manager as $manager)
                <li>
                  <a href="{{route('manager.dashboard',$manager->id)}}">{{$manager->fullName()}}
                  </a>
                </li>
              @endforeach
            </td>
            <td align="center">
              <a href="{{route('lead.branch',$branch->id)}}"> 
                {{$branch->leads_count}}
              </a>
            </td>
            
            <td align="center">
              <a href="{{route('opportunities.branch',$branch->id)}}">
                {{$branch->open}}
              </a>
            </td>
            <td align="center">
              <a href="{{route('activity.branch',$branch->id)}}">
                 {{$branch->activities_count}}
              </a>
            </td>
            <td align="center">
              @if($branch->won >0)
              <a href="{{route('opportunities.branch',$branch->id)}}">
                {{$branch->won}}
              </a> 
              @else
               0 
               @endif
            </td>
            <td  align="center"> 
              @if($branch->lost >0)
              <a href="{{route('opportunities.branch',$branch->id)}}">
                {{$branch->lost}}
              </a> 
            @else 
              0 
            @endif
          </td>
        </tr>
       @endforeach
  </tbody>
</table>