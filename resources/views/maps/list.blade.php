@extends('site.layouts.default')


@if ($data['type'] == 'branch')
	<?php $fields = array('Branch Name'=>'branchname','Service Line'=>'servicelines','Address'=>'street','City'=>'city','State'=>'state','ZIP'=>'zip','Miles'=>'distance_in_mi'); 
?>
@else
<?php $fields = array('Business Name'=>'businessname','National Acct'=>'companyname','Address'=>'street','City'=>'city','State'=>'state','ZIP'=>'zip','Miles'=>'distance_in_mi','Watch'=>'watch_list'); 
?>

@endif
@section('content')


 

<h1>{{$data['title']}}</h1>

{{$filtered ? "<h4 class='filtered'>Filtered</h4>" : ''}}
<p><a href="/watch" title="Review my watch list"><i class="glyphicon glyphicon-th-list"></i> View My Watch List</a></p>
<p><a href="/watchexport" title="Download my watch list as a CSV / Excel file"><i class="glyphicon glyphicon-cloud-download"></i> Download My Watch List</a> </p>

@include('maps/partials/_form')
@include('partials.advancedsearch')
<table id='sorttable' class ='table table-bordered table-striped table-hover dataTable'><thead>
		 @foreach($fields as $key=>$value)
			<th><span>{{$key}}</span>
			<!-- @if ($key == 'Watch')
			more research needed!
			<input type="checkbox" id="selecctall"/> Select All</li>

			@endif -->
			</th>
			
		@endforeach
		</thead>

        @foreach($data['result'] as $row)
			<?php reset ($fields);?>
			<tr>
			 @foreach($fields as $key=>$field)
            @if($field == 'watch_list')
           	 <td style ="text-align: center; vertical-align: middle;">
           @else
            	<td>
            @endif
				<?php switch ($field) {
					
					case 'businessname':
				
					echo "<a href=\"/location/";
					echo  $row->id."\">" . $row->$field ."</a>";
					break;
					
					case 'companyname':
				
					echo "<a href=\"/company/";
					echo $row->company_id."\">" . $row->$field  ."</a>";
					break;
					
					case 'branchname':
				
						echo "<a href=\"/branch/" . $row->id."\">";
						echo $row->$field ."</a>";
					break;
					
					
					
					case 'distance_in_mi':
						echo  number_format($row->$field ,2) ;	
					break;
					
					case 'watch_list':
						
						if(isset($watchlist) && in_array($row->id,$watchlist)) {
							echo "<input checked ";
							
							
						}else{
							
							echo "<input ";
							
							
						}
						echo "id=\"".$row->id."\" ";
						echo " type='checkbox' name='watchList' ";
						echo "class='watchItem' value='".$row->id."' >";
			
			
					break;
					
					default:
				
						echo  $row->$field ;
					break;
				};?>
                </td>
			@endforeach
			</tr>
		@endforeach
        
</table>
@include('partials/_scripts')

@stop
