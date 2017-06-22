<!-- source -->
<div class="form-group{{ $errors->has('source') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label">Source Name</label>
    <div class="col-md-6">
        <input required type="text" class="form-control" name='source' description="source" value="{{ old('source', isset($leadsource) ? $leadsource->source : '' )}}" placeholder="source">
        <span class="help-block">
            <strong>{{ $errors->has('source') ? $errors->first('source') : ''}}</strong>
        </span>
    </div>
</div>
<!-- Description -->
<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label">Description</label>
    <div class="col-md-6">
        <textarea required class="form-control" name='description' title="description">{{ old('description', isset($leadsource) ? $leadsource->description : '')}}</textarea>

        <span class="help-block">
            <strong>{{$errors->has('description') ? $errors->first('description')  : ''}}</strong>
        </span>

    </div>
</div> 
<!-- Reference -->
<div class="form-group{{ $errors->has('reference') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label">Reference</label>
    <div class="col-md-6">
        <input type="text" class="form-control" name='reference' description="reference" value="{{ old('reference', isset($leadsource) ? $leadsource->reference : "")}}" placeholder="reference">
        <span class="help-block">
            <strong>{{ $errors->has('reference') ? $errors->first('reference') : ''}}</strong>
        </span>
    </div>
</div>
<!-- Dates from / to -->
<legend>Available From / To</legend>
    <div class="form-group{{ $errors->has('from)') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label" for="datefrom">Available From</label>
        <div class="input-group input-group-lg">
            <input required class="form-control" type="text" name="datefrom"  id="fromdatepicker" 
            value="{{  old('datefrom', isset($leadsource) ? $leadsource->datefrom->format('m/d/Y'): date('m/d/Y')) }}"/>

            <span class="help-block">
                <strong>{{$errors->has('datefrom') ? $errors->first('datefrom')  : ''}}</strong>
            </span>
        </div>
    </div>

    <div class="form-group{{ $errors->has('dateto') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label" for="dateto">Available To</label>
        <div class="input-group input-group-lg ">
            <input requried class="form-control" type="text" name="dateto"  id="todatepicker" 
            value="{{  old('dateto', isset($leadsource) ?  $leadsource->dateto->format('m/d/Y') : date('m/d/Y',strtotime('+3 months'))) }}"/>

            <span class="help-block">
                <strong>{{$errors->has('dateto') ? $errors->first('dateto')  : ''}}</strong>
            </span>
        </div>
    </div>

@include('salesactivity.partials._verticals')  

  <script>
$(function () {
    
    
    
    $('li :checkbox').on('click', function () {
    var $chk = $(this),
        $li = $chk.closest('li'),
        $ul, $parent;
    if ($li.has('ul')) {
        $li.find(':checkbox').not(this).not(":disabled").prop('checked', this.checked)
    }
    do {
        $ul = $li.parent();
        $parent = $ul.siblings(':checkbox');
        if ($chk.is(':checked')) {
            $parent.prop('checked', $ul.has(':checkbox:not(:checked)').length == 0)
        } else {
            $parent.prop('checked', false)
        }
        $chk = $parent;
        $li = $chk.closest('li');
    } while ($ul.is(':not(.someclass)'));
});
    
    
    $("#searchsave").click(function(){
            var searchdata = $('#filterForm :input').serialize();
            
            $.post("/api/advancedsearch",searchdata,function(response,status){
                
                window.location.reload(true);});
            
                
            }); 
    }); 


    </script>