<table class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      <th>Person</th>
      <th>Role</th>
      <th>Last Login</th>
      <th>Mapminer Manager</th>
      <th>Oracle Manager</th>
      
        
    </th>
  </thead>
  <tbody>
        @foreach ($users as $user)
           
            <tr>
                <td>
                    @if(isset($user->mapminerUser->person))
                        <a href="{{route('users.show', $user->mapminerUser->id)}}">
                            {{$user->mapminerUser->fullName()}}
                        </a>
                    @else
                    {{$user->primary_email}}
                    @endif
                </td>
                <td>
                    @foreach ($user->mapminerUser->roles as $role)
                        {{$role->display_name}}
                    @endforeach
                </td>
                <td>{{$user->mapminerUser->lastlogin}}</td>
                <td>
                    @if(isset($user->mapminerUser->person->reportsTo->id))
                    <a href="{{route('users.show',$user->mapminerUser->person->reportsTo->id)}}">
                        {{$user->mapminerUser->person->reportsTo->fullName()}}
                    </a>
                    @endif
                </td>
                <td>
                    <a href="{{route('oracle.show',$user->oracleManager->id)}}">
                        
                            {{$user->oracleManager->fullName()}}
                    </a>   
                </td>
                
            </tr>
        @endforeach
       
    </tbody>
</table>
