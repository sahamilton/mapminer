<table>
 <tbody>
     <tr>
     @foreach($fields as $title=>$field)
    <td>
    {{$field}}
    </td>
    @endforeach
       
    </tr>
 
   @foreach($notes as $note)
    <tr>  
	<?php reset($fields);?>
   
    @foreach($fields as $key=>$field)
    <td>
    <?php 
	
	switch ($key) {
		case 'Location Name':
			
			echo $note->$field."</a>";
		
		
		break;
		
		
		case 'Posted':
			
			echo date('d/m/Y',strtotime($note->$field));
		break;
			
		
		default:
			echo $note->$field;
		break;
		
	};?>
	</td>

    @endforeach
    </tr>
   @endforeach
    
    </tbody>
    </table>

