@extends('site/layouts/default')
@section('content')

<h1>My Watch List</h1>

<p><a href="/watchmap" title="Review my watch list"><i class="glyphicon glyphicon-flag"></i> View My Watch Map</a> 

<a href="/watchexport" title="Download my watch list as a CSV / Excel file"><i class="glyphicon glyphicon-cloud-download"></i> Download My Watch List</a></p>



<table id='sorttable' class ='table table-bordered table-striped table-hover dataTable'><thead>
		@foreach($fields as $key=>$field)
			<th><span>{{$key}}</span>
			
			</th>
		@endforeach
        
		</thead>
<tbody>

 @foreach($watch as $row)


			<?php reset ($fields);?>
			<tr>
			@foreach($fields as $key=>$field)
				<?php switch ($field) {
					
					case 'businessname':
				
					echo "<td><a href=\"/location/";
					echo  $row['watching'][0]->id."\">";
					echo  $row['watching'][0]->$field  ."</a></td>";
					break;
					
					case 'companyname':
				
					echo "<td><a href=\"/company/";
					echo $row['watching'][0]->company->id."\">";
					echo $row['watching'][0]->company->$field ."</a></td>";
					break;
						
					case 'watch_list':
						echo "<td style =\"text-align: center; vertical-align: middle;\">";
						echo "<input checked id=\"".$row['watching'][0]->id."\"";
						echo " type='checkbox' name='watchList' ";
						echo " class='watchItem' value='".$row['watching'][0]->id."' ></td>";
					
					break;
					
					case 'notes':
					
						echo "<td>";
						if(isset($row['watchnotes']))
							{ 
								
								foreach($row['watchnotes'] as $notes)
								{
									echo $notes->note . "<br />";
								}
							}
						?>
                        <a 
                        class="addLocationId"
                        data-toggle="modal" 
                        data-id = "{{$row['watching'][0]->id}}"
                        data-title = "{{$row['watching'][0]->businessname}}"
                        href="#noteform"
                        title="add new note to {{$row['watching'][0]->businessname}} location">
                <i class="glyphicon glyphicon-plus"></i></a></li>
						<?php echo "</td>";
					break;
					
					default:
				
					echo "<td>" . $row['watching'][0]->$field ."</td>";
					break;
				};?>
			@endforeach
			</tr>
@endforeach

       </table>
@include('partials/_scripts')

<script>
$(document).on("click", ".addLocationId", function () {
	var title = "Add note to " + $(this).data('title') + " location.";
	var locationID = $(this).data('id');
	$(".modal-body #location_id").val( locationID );
	$(".modal-header #myModalLabel").text( title );
     
});
</script>
@include('watch.partials._note')
@stop