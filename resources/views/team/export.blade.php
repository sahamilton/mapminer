<table>

    <tbody>
    	<tr>
	        <th>Person</th>
	        <th>Role</th>
	        <th>Logins</th>
	        <th>First Login</th>
	        <th>Last Login</th> 
   		</tr>
        @foreach ($people as $person)
        <tr>
            <td>{{$person->fullName}}</td>
            <td>
                @if(isset($person->userdetails))
                    @foreach ($person->userdetails->roles as $role)
                       {{$role->display_name}}
                       @if(! $loop->last),@endif
                    @endforeach
                @else
                    {{dd($person)}}
                @endif
            </td>
            <td>{{$person->userdetails->usage->count()}}</td>
            @if($person->userdetails->usage->count()>1)
            <td>{{$person->userdetails->usage->min('created_at')->format('Y-m-d')}}</td>
            <td>{{$person->userdetails->usage->max('lastactivity')->format('Y-m-d')}}</td>
            @else
            <td></td><td>Never Logged In</td>
            @endif
        </tr>
        @endforeach

    </tbody>
</table>