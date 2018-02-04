@if(auth()->user()->hasRole('Admin') or $project->owned())

<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		<th>type</th>
            <th>firm
                  @include('projects.partials._addfirm')
            </th>
            <th>contact</th>
            <th>addr1</th>
            <th>addr2</th>
            <th>city</th>
            <th>state</th>
            <th>zipcode</th>
            <th>phone</th>

	</thead>
	<tbody>
            @foreach ($project->companies as $company)
                  <tr>
                        <td>{{$company->pivot->type}}</td>
                        <td><a href="{{route('projectcompany.show',$company->id)}}"
                        title="See all {{$company->firm}} construction projects">
                        {{$company->firm}}</a></td>
                         <td>
                        <p>@include('projects.partials._addcontacts')</p>
                              @if(count($company->employee)>0)
                                    <table class="table table-bordered table-condensed">

                                          <tbody>
                                          @foreach ($company->employee as $employee)
                                                <tr>
                                                      <td> {{$employee->contact}}</td>
                                                      <td>{{$employee->title}}</td>
                                                      <td>{{$employee->phone}}</td>
                                                      <td>{{$employee->email}}</td>
                                                </tr>
                                          @endforeach
                                    </tbody>
                                    </table>
                              @endif
                        </td>
                        <td>{{$company->addr1}}</td>
                        <td>{{$company->addr2}}</td>
                        <td>{{$company->city}}</td>
                        <td>{{$company->state}}</td>
                        <td>{{$company->zipcode}}</td>
                        <td>{{$company->phone}}</td>
                  </tr>
            @endforeach
      </tbody>
</table>
@include('partials._contactmodal')
@else

<div class="alert alert-danger">
@if(count($project->owner)>0)

<p>Project has been {{$project->owner[0]->pivot->status}} by {{$project->owner[0]->postName()}}</p>

@else
<p>You need to claim this project before you can see the project contacts</p>
@endif
</div>
@endif