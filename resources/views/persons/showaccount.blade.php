@extends('site/layouts/default')
@section('content')


<h3>Accounts managed by {{$people->firstname}} {{$people->lastname}}</h3>
<p><a href="mailto:{{$people->email}}" title="Email {{$people->firstname}} {{$people->lastname}}">{{$people->email}}</a> </p>
 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    @foreach($fields as $key=>$field)
    <th>
    {{$key}}
    </th>
    @endforeach
       
    </thead>

    <tbody>
   @foreach($accounts as $account)
    <tr>  
	<?php reset($fields);?>
   @foreach($fields as $key=>$field)
    <td>
	<?php 
	
	switch ($key) {
				
		case 'Account':
		
			$title = "See all ". $account->companyname."locations";
			if(isset( $account->countlocations->first()->count) &&  $account->countlocations->first()->count > 0){
				echo "<a href=\"/company/".$account->id."\" title=\"".$title."\">".$account->companyname."</a>";
			}else{
				echo "<a title=\"".$account->companyname." has no locations.\">".$account->companyname."</a>";
			}
			
		
		break;
		
		case 'Vertical': 
			if(isset($account->industryVertical->filter))
			{
				$vertical = $account->industryVertical->filter;
				$title = "See all ". $vertical ." accounts.";
				echo "<a href=\"" . route('company.vertical',$account->industryVertical->id). "\" title=\"".$title."\">".$vertical."</a>";
				
			}else{
				echo "Not Assigned";
				
			}
			
			
		break;
		
		default:
			echo $person->$field;
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