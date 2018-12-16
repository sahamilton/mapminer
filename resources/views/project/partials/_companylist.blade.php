@if(auth()->user()->hasRole('Admin') or $location->project->owner())

<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		<th>type</th>
            <th>firm</th>
            <th>contact</th>
            <th>addr1</th>
            <th>address2</th>
            <th>city</th>
            <th>state</th>
            <th>zip</th>
            <th>phone</th>

	</thead>
	<tbody>
            @foreach ($location->project->companies as $company)
                  <tr>
                        <td>{{$company->pivot->type}}</td>
                        <td><a href="{{route('projectcompany.show',$company->id)}}"
                        title="See all {{$company->firm}} construction projects">
                        {{$company->firm}}</a></td>
                         <td>
                        <p>@include('projects.partials._addcontacts')</p>
                              @if($company->employee->count()>0)
                                    <table class="table table-bordered table-condensed">

                                          <tbody>
                                          @foreach ($company->employee as $employee)
                                                <tr>
                                                      <td> {{$employee->contact}}</td>
                                                      <td>{{$employee->title}}</td>
                                                      <td>{{$employee->contactphone}}</td>
                                                      <td>{{$employee->email}}</td>
                                                </tr>
                                          @endforeach
                                    </tbody>
                                    </table>
                              @endif
                        </td>
                        <td>{{$company->addr1}}</td>
                        <td>{{$company->address2}}</td>
                        <td>{{$company->city}}</td>
                        <td>{{$company->state}}</td>
                        <td>{{$company->zip}}</td>
                        <td>{{$company->phone}}</td>
                  </tr>
            @endforeach
      </tbody>
</table>
@include('project.partials._contactmodal')
@else

<div class="alert alert-danger">
@if($location->project->owner->count()>0)

<p>Project has been {{$location->project->owner[0]->pivot->status}} by {{$location->project->owner[0]->fullName()}}</p>

@else
<p>You need to claim this project before you can see the project contacts</p>
@endif
</div>
@endif