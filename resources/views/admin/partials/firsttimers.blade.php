<div style="border:1px solid #000;width:300px;margin:20px;padding:20px;float:left">
  <h4>First Time Users in Past Month</h4>
  <table id ='sorttable2' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      <th>First Time Users</th>
      <th>Role</th>
    </thead>
    <tbody>
      @foreach($data['firsttimers'] as $first)
        <tr>
          <td>
            <a href="{{route('users.show',$first->uid)}}"
              title="Review {{$first->fullname}}'s details">
              {{$first->fullname}}</a>
            </a>
          </td>
          <td>{{$first->role}}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>