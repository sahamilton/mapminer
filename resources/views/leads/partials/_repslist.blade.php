<h2>Closest Sales Reps </h2>

@if(count($people)>0)
<form action = "{{route('webleads.assign')}}" name="assignlead" method="post">
{{csrf_field()}}
    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
        <thead>

            <th>First Name</th>
            <th>Last Name</th>
            <th>Branches</th>

            <th>Distance</th>
            <th>Assign</th>
        </thead>
        <tbody>
        @foreach($people  as $person)
            <tr> 

                <td><a href="{{route('salesorg',$person->id)}}">{{$person->firstname}}</a></td>
                <td>{{$person->lastname}}</td>
                <td>
                    @foreach($person->branchesServiced as $branch)
                        <li>{{$branch->branchname}}</li>
                    @endforeach

                </td> 
                <td>{{number_format($person->distance,2)}}</td> 
                <td><input type="checkbox" name="assign[]" value="{{$person->id}}" /></td>
            </tr>
        @endforeach
        </tbody>
    </table>
<input type="submit" class="btn btn-info" value="Assign Prospects" />
<input type="hidden" name="lead_id" value="{{$lead->id}}" />
</form>
@endif
