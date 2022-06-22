
<table class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		<tr>
			<th>  
                <a wire:click.prevent="sortBy('display_name')" role="button" href="#">
                    Permission
                    @include('includes._sort-icon', ['field' => 'display_name'])
                </a>
                   
            </th>
			
			<th class="col-md-2">Roles</th>
			
            <th class="col-md-2">Actions</th>
			
		</tr>
	</thead>
	<tbody>
	    @foreach ($permissions as $permission)

		    <tr>
				<td><a href="{{route('roles.show',$permission->id)}}" >{{$permission->display_name}}</td>
				<td>
					<ul>
						@foreach($permission->roles as $role)
							<li>{{ucwords($role->display_name)}}</li>
						@endforeach
					</ul>
				</td>
			   
			    <td>
					<div class="btn-group">
						<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
						<span class="caret"></span>
						<span class="sr-only">Toggle Dropdown</span>
						</button>
						<ul class="dropdown-menu" role="menu">

							<a class="dropdown-item" 
								href="{{route('permissions.edit',$permission->id)}}">
								<i class="far fa-edit text-info"" aria-hidden="true"> </i>Edit {{$permission->display_name}}
							</a>
							<a class="dropdown-item"
								data-href="{{route('permissions.destroy',$permission->id)}}" 
								data-toggle="modal" 
								data-target="#confirm-delete" 
								data-title = "{{$permission->display_name}}" 
								href="#">
								<i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> Delete {{$permission->display_name}}
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

