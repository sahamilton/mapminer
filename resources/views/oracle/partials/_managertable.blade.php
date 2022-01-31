<table class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      <th>Person</th>
      <th>Mapminer Manager</th>
      <th>Oracle Manager</th>
      <th>
        <button wire:click='reassignAll' class="btn btn-warning">
            Reassign All 
        </button>
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
                    @if(isset($user->mapminerUser->person->reportsTo->id))
                    <a href="{{route('users.show',$user->mapminerUser->person->reportsTo->id)}}">
                        {{$user->mapminerUser->person->reportsTo->fullName()}}
                    </a>
                    @endif
                </td>
                <td>
                    @if(isset($user->oracleManager->mapminerUser->person))
                        <a href="{{route('users.show',$user->oracleManager->mapminerUser->person->reportsTo->id)}}">
                            {{$user->oracleManager->mapminerUser->person->fullName()}}
                        </a>
                        
                    @endif
                </td>
                <td>
                    <a href="" wire:click="reassign({{$user->id}})" 
                        class="text-warning"
                        title="reassign {{$user->mapminerUser->person->firstname}} to {{$user->oracleManager->mapminerUser->person->firstname}}" >
                        <i class="fas fa-exchange-alt"></i>
                            </a>
            </tr>
        @endforeach
       
    </tbody>
</table>
