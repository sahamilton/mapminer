
<table class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		<tr>
			<th>  
                <a wire:click.prevent="sortBy('display_name')" role="button" href="#">
                    Role
                    @include('includes._sort-icon', ['field' => 'display_name'])
                </a>
                   
            </th>
			
			<th class="col-md-2">Permissions</th>
			<th>
				
				<a wire:click.prevent="sortBy('assigned_roles_count')" role="button" href="#">
                   Count
                    @include('includes._sort-icon', ['field' => 'assigned_roles_count'])
                </a>
			</th>

            <th class="col-md-2">Actions</th>
			
		</tr>
	</thead>
	<tbody>
	    @foreach ($roles as $role)

		    <tr>
				<td><a href="{{route('roles.show',$role->id)}}" >{{$role->display_name}}</td>
				<td>
					<ul>
						@foreach($role->permissions as $permission)
							<li>{{ucwords($permission->display_name)}}</li>
						@endforeach
					</ul>
				</td>
			    <td>{{$role->assignedRoles->count()}}
			    <td>
					<div class="btn-group">
						<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
						<span class="caret"></span>
						<span class="sr-only">Toggle Dropdown</span>
						</button>
						<ul class="dropdown-menu" role="menu">

							<a class="dropdown-item" 
								href="{{route('roles.edit',$role->id)}}">
								<i class="far fa-edit text-info"" aria-hidden="true"> </i>Edit {{$role->display_name}}
							</a>
							<a class="dropdown-item"
								data-href="{{route('roles.destroy',$role->id)}}" 
								data-toggle="modal" 
								data-target="#confirm-delete" 
								data-title = "{{$role->display_name}}" 
								href="#">
								<i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> Delete {{$role->display_name}}
							</a>

						</ul>
					</div>
			    
			    
			    </td>
			    
			</tr>
	    @endforeach
	</tbody>
</table>
@include('partials/_modal')
@include('partials/_scripts')

