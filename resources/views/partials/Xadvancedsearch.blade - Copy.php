<div class="modal fade" id="advancedSearch" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Advanced Search Options</h4>
            </div>
            <div id='form' class="modal-body">
               
                <form id ='selectForm'>
<div>


<fieldset><legend>Business Type</legend>
<div>
<?php $types= ['Prior/Current Client','New Business Opportunity','Both'];
$verticals =array('Waste Management'=>array('Hauling','Recycling'),'Auctions'=>'Auction','Hospitality'=>array('Kitchen','Cleaning'));

?>
        @foreach ($types as $type)
        @if(null!== Session::get('businesstype'))
        	 @if($type ==Session::get('businesstype'))
            	{{Form::radio('businesstype',$type,true)}} {{$type}}  
            @else
            	{{Form::radio('businesstype',$type)}} {{$type}} 
            @endif
        
        @else
            @if($type =='Both')
            {{Form::radio('businesstype',$type,true)}} {{$type}}  
            @else
            {{Form::radio('businesstype',$type)}} {{$type}} 
            @endif
        @endif
        @endforeach
        </div></fieldset></div>
        
        <div>
<fieldset><legend>Industry & Segment</legend>
        <div id="vertical">
        <ul style="list-style-type: none">
        <li><input checked type="checkbox" name= "checkAll"id="checkAll" />Check All
        <ul style="list-style-type: none">
        <?php while(list($key,$value)= each($verticals)){
            	echo "<li><input checked type=\"checkbox\" name= \"vertical[]\" value=\"".$key."\" />". $key;
				if(is_array($value)){
				echo "<ul style=\"list-style-type: none\";>";
					while(list($subkey,$subvalue)= each($value)){
						echo "<li><input checked type=\"checkbox\" name= \"".$key."|segment[]\" value=\"".$subvalue."\" />". $subvalue."</li>";
					}
				echo "</ul>";
				}
				echo "</li>";
        }?>
        </li></ul></ul>
        </div></fieldset></div>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id='reset'>Reset</button>
                <button id='save' type="button" value='Save' class="btn btn-primary" data-dismiss="modal">Save</button>
        </div>   </form> </div>
        </div>

</div>


<script>
$(function () {
    $("input[type='checkbox']").change(function () {
        $(this).siblings('ul')
            .find("input[type='checkbox']")
            .prop('checked', this.checked);
    });
	
	
	
	
	
	$("#save").click(function(){
			var searchdata = $('#selectForm :input').serialize();
			
			$.post("/api/advancedsearch",searchdata,function(response,status){
				window.location.reload(true);});
			
				
			});	
	});	


	</script>

