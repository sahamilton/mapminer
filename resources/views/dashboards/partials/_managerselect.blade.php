@foreach ($managers as $manager)
    <tr>
        <td><a href="{{route('newdashboard.manager', $manager->id)}}">{{$manager->fullName()}}</a></td>
        <td>{{implode(",",$manager->userdetails->roles->pluck('display_name')->toArray())}}</td>
        <td>{{isset($manager->reportsTo) ? $manager->reportsTo->fullName() : ''}}</td>
        <td>
            @if($manager->userdetails->hasRole(['branch_manager'])) 
                @foreach ($manager->branchesServiced as $branch) 
                    <a href="{{route('branchdashboard.show',$branch->id)}}">{{$branch->id}}</a>,
                @endforeach
            @endif
        </td>
    </tr>
@endforeach