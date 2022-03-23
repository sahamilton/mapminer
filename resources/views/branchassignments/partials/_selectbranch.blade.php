<tr class="{{$details->branchesServiced()->exists() && $details->branchesServiced->contains($branch->id) ? 'highlight' :''}} item">
    <td><a href="{{route('branches.show',$branch->id)}}" title="Review {{trim($branch->branchname)}} branch">{{$branch->branchname}}</a></td>
    <td>{{$branch->id}}</td>
    <td>{{$branch->fullAddress()}}</td>
    <td>
      @if($branch->manager->count() > 0)
      @foreach ($branch->manager as $mgr)
      
        {{$mgr->fullName()}} {{! $loop->last ? "<br />" :''}}

      @endforeach      @endif
    </td>
    <td>{{number_format($branch->distance,1)}} miles</td>
    <td>
   
      <input class="text text-success" 
      type="checkbox"
      {{$branch->manager->count() > 0 ? 'disabled' : ''}}
      id="branch[{{$branch->id}}]" 
      name ="branch[{{$branch->id}}]" 
      value="{{$branch->id}}"
      checked >
    </td>
    
   </tr>