<tr class="{{$details->branchesServiced()->exists() && $details->branchesServiced->contains($branch->id) ? 'highlight' :''}} item">
    <td><a href="{{route('branches.show',$branch->id)}}" title="Review {{trim($branch->branchname)}} branch">{{$branch->branchname}}</a></td>
    <td>{{$branch->id}}</td>
    <td>{{$branch->street}}</td>
    <td>{{$branch->city}}</td>
    <td>{{$branch->state}}</td>
    <td>{{number_format($branch->distance,1)}} miles</td>
    <td>
      <input class="text text-success" 
      type="checkbox"
      {{$details->branchesServiced()->exists() && $details->branchesServiced->contains($branch->id) ? 'checked' :''}} 
      id="branch[{{$branch->id}}]" 
      name ="branch[]" 
      value="{{$branch->id}}">
    </td>

   </tr>