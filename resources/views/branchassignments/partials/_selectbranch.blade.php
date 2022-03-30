<tr class="{{$details->branchesServiced()->exists() && $details->branchesServiced->contains($branch->id) ? 'highlight' :''}} item">
    <td><a href="{{route('branches.show',$branch->id)}}" title="Review {{trim($branch->branchname)}} branch">{{$branch->branchname}}</a></td>
    <td>{{$branch->id}}</td>
    <td>{{$branch->fullAddress()}}</td>
    <td>
      @if($branch->manager->count() > 0)
        @foreach ($branch->manager as $mgr)
       
          {{$mgr->fullName()}}
          @if ($mgr->reports_to != $details->id )
          <i class="fa-solid fa-exclamation text-danger" title="{{$mgr->fullName()}} does not report to {{$details->fullName()}}"></i>
          @endif
          {{ ! $loop->last ? "; " :'' }}

        @endforeach      
      @endif
    </td>
    <td>{{number_format($branch->distance,1)}} miles</td>
    <td>
   
      <input class="text text-success" 
      type="checkbox"
      {{$branch->manager->count() > 0 && $branch->manager->first()->reports_to === $details->id ? 'disabled' : ''}}
      id="branch[{{$branch->id}}]" 
      name ="branch[{{$branch->id}}]" 
      value="{{$branch->id}}"
      checked >
    </td>
    
   </tr>