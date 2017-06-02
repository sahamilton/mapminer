<!-- Import file -->
<legend>File Location:</legend>
<div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
     <label for="location" >Upload File Location</label>
     
         <input required type="file" class="form-control" name='file' id='file' description="file" 
         value="{{ old('file')}}">
         <strong>{!! $errors->first('file', '<p class="help-block">:message</p>') !!}</strong>
     </div>
 </div>


<legend>Relates To:</legend>
@include('leads.partials.selectors')

<!-- Dates from / to -->
<legend>Available From / To</legend>
<div class="form-group{{ $errors->has('from)') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label" for="datefrom">Available From</label>
    <div class="input-group input-group-lg">
	<input class="form-control" required type="text" name="datefrom"  id="fromdatepicker" 
value="{{  old('datefrom', isset($lead->datefrom) ? $lead->datefrom->format('m/d/Y'): date('m/d/Y')) }}"/>

               <strong>{!! $errors->first('datefrom', '<p class="help-block">:message</p>') !!}</strong>
</div>
</div>

<div class="form-group{{ $errors->has('dateto') ? ' has-error' : '' }}">
<label class="col-md-4 control-label" for="dateto">Available To</label>
<div class="input-group input-group-lg ">
<input class="form-control" required type="text" name="dateto"  id="todatepicker" 
value="{{  old('dateto', isset($lead) ?  $lead->dateto->format('m/d/Y') : date('m/d/Y',strtotime('+1 years'))) }}"/>

        <strong>{!! $errors->first('dateto', '<p class="help-block has-error">:message</p>') !!}</strong>
</div>
</div>


<!-- Lead Source -->
<legend>Select or Create new Lead Source</legend>   
		<div class="form-group{{ $errors->has('lead_source_id') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">Lead Source</label>
        <div class="input-group input-group-lg ">
            <select id="leadsource" class="form-control" name='lead_source_id'>

            @foreach ($sources as $key=>$value)
            	<option {{isset($lead) && ($lead->lead_source_id == $key) ? 'selected' : '' }} value="{{$key}}">{{$value}}</option>

            @endforeach


            </select>
          
            <input type="text" id="addElement" name="addElement" placeholder= "Add Item"/><button type="button" onclick="addOption()">Insert new source</button>
            <strong>{!! $errors->first('lead_source_id', '<p class="help-block">:message</p>') !!}</strong>
        </div>
    </div>


<script>
function addOption() {
    var x = document.getElementById("leadsource");
    var option = document.createElement("option");
    var add = document.getElementById("addElement");
    option.text = add.value;
    x.add(option, x[0]);
    document.getElementById('leadsource').value = add.value;
}
</script>

    