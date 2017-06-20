@extends('admin.layouts.default')
@section('content')

<h1>All How To Fields</h1>

<div class="pull-right">
				<a href="{{{ route('howtofields.create') }}}" class="btn btn-small btn-info iframe"><span class="glyphicon glyphicon-plus-sign"></span> Create New Field</a>
			</div>
    
    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     @foreach($fields as $key=>$value)
    <th>
    {{$key}}
    </th>
    @endforeach
       
    </thead>
    <tbody>
   @foreach($howtofields as $howtofield)
    <tr>  
	<?php reset($fields);?>
     @foreach($fields as $key=>$fields)
    <td><?php 
	
	switch ($key) {
		case 'Reqd':
			echo $howtofield->$field == '0' ? 'No' : 'Yes';
		
		break;
		
		case 'Actions':
		?>
			<div class="btn-group">
				  
				  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
					<span class="caret"></span>
					<span class="sr-only">Toggle Dropdown</span>
				  </button>
				  <ul class="dropdown-menu" role="menu">

					<li><a href="/admin/howtofields/<?php echo $howtofield->id;?>/edit"><i class="glyphicon glyphicon-pencil"></i>Edit
                    <?php echo $howtofield->fieldname;?>
                     </a></li>
					<li><a href="/admin/howtofields/<?php echo $howtofield->id;?>/delete" onclick="if(!confirm('Are you sure to delete this field and all its references?')){return false;};" title="Delete "><i class="glyphicon glyphicon-trash"></i> Delete <?php echo $howtofield->fieldname;?></a></li>
					
				  </ul>
				</div>
                <?php 
				break;
		
		default:
			echo $howtofield->$field;
		break;
		
	};?>
	
    </td>
    @endforeach
    </tr>
   @endforeach
    
    </tbody>
    </table>
@include('partials/_scripts')
@stop