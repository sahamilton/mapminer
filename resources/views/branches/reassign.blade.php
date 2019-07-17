@extends ('admin.layouts.default')
@section('content')
<h4>Reassign {{$branch->branchname}} Leads, Opportunities & Activities</h4>
<p><strong>{{$branch->branchname}} branch has</strong>:
    <ul>
        <li>{{$branch->allLeads->count()}} leads</li>
        <li>{{$branch->openOpportunities->count()}} open opportunities</li>
        <li>{{$branch->openActivities->count()}} open activities</li>
    </ul>
</p>
<fieldset><strong>Reassign to branch:</strong>
<h4>Nearest Branches</h4>
<form method="post" action="{{route('branch.reassign', $branch->id)}}">
    <table>
    <thead>
        <th>Branch</th>
        <th>City</th>
        <th>Distance</th>
        <th>Assign</th>
    </thead>
    <tbody>
        @foreach ($nearby as $near)
        @if($near->id != $branch->id)
        <tr>
            <td>{{$near->branchname}}</td>
            <td>{{$near->city}}</td>
            <td>{{number_format($near->distance,2)}} mi</td>
            <td><input type="radio" name="nearbranch" value="{{$near->id}}"></td>
        </tr>
        @endif
        @endforeach
    </tbody>
</table>

@csrf
<div class="col-sm-5">
    <div class="form-group">
        <label for='branch'><h4> All Branches:</h4></label>
            <select name="newbranch" class="form-control">
                <option value=""></option>
                @foreach ($branches as $br)
                    <option value="{{$br->id}}">{{$br->branchname}}</option>
                @endforeach
            </select>

        <div class="form-group">
            <input type="submit" class="btn btn-warning " name="Submit" value="Reassign" />
        </div>
        <input type="hidden" name="oldbranch" value="{{$branch->id}}" />
    </div>
</div>
</form>
</fieldset>
@endsection