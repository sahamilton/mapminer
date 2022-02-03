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
                    
                        <a href="{{route('users.show', $user->id)}}">
                            {{$user->person->fullName()}}
                        </a>
                    
                </td>
                <td>
                    @foreach ($user->roles as $role)
                        {{$role->display_name}}
                    @endforeach
                </td>
                <td>{{$user->lastlogin}}</td>
                <td>
                   
                    
                </td>
                <td>
                    @if(isset($user->oracleMatch->oracleManager))
                        
                            {{$user->oracleMatch->oracleManager->fullName()}}
                    @endif
                    
                </td>
                
            </tr>
        @endforeach
       
    </tbody>
</table>
