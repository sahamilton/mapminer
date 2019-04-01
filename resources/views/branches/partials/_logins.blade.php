<table id='sorttable3' class ='table table-bordered table-striped table-hover'>
  <thead>
    <th>Team Member</th>
    <th>First Login</th>
    <th>Last Login</th>
    <th>Total Logins</th>
  </thead>
  <tbody>
    @foreach ($data['teamlogins'] as $login)
   
      @foreach ($login as $person=>$login)

      <tr>
        <td>{{$person}}</td>
        @if(isset($login['first']))
        <td>{{$login['first']->format('Y-m-d')}}</td>
        <td>{{$login['last']->format('Y-m-d')}}</td>  
        <td>{{$login['count']}}</td>
          @else
            <td>Never logged in</td>
            <td></td>
            <td></td>
          @endif        
        
      </tr>
      
    @endforeach
    @endforeach
  </tbody>
  
</table>


