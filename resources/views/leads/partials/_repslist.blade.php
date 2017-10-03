<h1>Closest Sales Reps </h1>

@if(count($people)>0)
<form action = "{{route('leads.assign')}}" name="assignlead" method="post">
{{csrf_field()}}
    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <th>Employee Id</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Role</th>
            <th>Email</th>
            <th>Distance</th>
            <th>Assign</th>
        </thead>
        <tbody>
        @foreach($people  as $person)
            <tr> 
                <td>{{$person->employee_id}}</td>
                <td><a href="{{route('salesorg',$person->id)}}">{{$person->firstname}}</a></td>
                <td>{{$person->lastname}}</td> 
                <td>{{$person->role}}</td>
                <td>{{$person->email}}</td> 
                <td>{{number_format($person->distance_in_mi,2)}}</td> 
                <td><input type="checkbox" name="assign[]" value="{{$person->id}}" /></td>
            </tr>
        @endforeach
        </tbody>
    </table>
<input type="submit" class="btn btn-info" value="Assign Prospects" />
<input type="hidden" name="lead_id" value="{{$lead->id}}" />
</form>
@endif
