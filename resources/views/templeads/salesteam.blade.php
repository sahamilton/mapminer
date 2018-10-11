@extends('admin.layouts.default')
@section('content')
<table>
    <thead>
        <th>ID</th>
        <th>Sales Rep</th>
        <th>Role</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </thead>
    @foreach ($salesteam as $team)
    <tr>
        
        <td>{{$team->id}}</td>
        <td>{{$team->postName()}}</td>
        <td>{{$team->userdetails->roles->first()->name}}</td>
            @foreach ($team->getAncestors()->reverse() as $managers)
                @if ($loop->first)
                
                    @php echo str_repeat('<td></td><td></td>',4-$managers->depth)  @endphp
               
                @endif
                @if($managers->depth !=0)
                <td>{{$managers->postName()}}</td>
                <td>{{$managers->userdetails()->first()->roles()->first()->name}}</td>
                @endif
            @endforeach
	</tr>	
    @endforeach 
</table>   
@include('partials/_scripts')
@endsection
