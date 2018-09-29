<tr>
    <td><a href="{{route('branches.show',$branch->id)}}" title="Review {{trim($branch->branchname)}} branch">{{$branch->branchname}}</a></td>
    <td>{{$branch->id}}</td>
    <td>{{$branch->street}}</td>
    <td>{{$branch->city}}</td>
    <td>{{$branch->state}}</td>
    <td>
      <input class="text text-success" type="checkbox" checked name ="branch[]" value="{{$branch->id}}">
    </td>
   </tr>