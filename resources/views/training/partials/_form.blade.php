
<!--- Title -->

    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Title</label>
              <div class="input-group input-group-lg ">
            <input type="text" required 
            class="form-control" 
            name='title' 
            description="title" 
            value="{{ old('title' , isset($training) ? $training->title : "" ) }}" 
            placeholder="title">
            <span class="help-block">
                <strong>{{ $errors->has('title') ? $errors->first('title') : ''}}</strong>
                </span>
        </div>
    </div>

<!-- Description -->
	 <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
         <label class="col-md-2 control-label">Description</label>
          <div class="input-group input-group-lg ">
             <textarea class="form-control" 
             name='descripition' 
             title="descripition">
             {!! old('description', isset($training) ? $training->description : '') !!}
             </textarea>
                 <span class="help-block">
                 <strong>{{$errors->has('description') ? $errors->first('description')  : ''}}</strong>
                 </span>
 
         </div>
     </div> 
<!-- reference -->
    <div class="form-group{{ $errors->has('reference') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Reference</label>
        <div class="input-group input-group-lg ">
                <input type="text" 
                class="form-control" 
                name='reference' 
                description="reference" 
                required
                value="{!! old('reference') , isset($training) ? $training->reference : "" !!}" placeholder="reference">
                <span class="help-block">
                    <strong>{{ $errors->has('reference') ? $errors->first('reference') : ''}}</strong>
                    </span>
            </div>
    </div>
 <!-- type -->
        <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
            <label class="col-md-2 control-label">Type</label>
              <div class="input-group input-group-lg ">
                    <input 
                    type="text" 
                    class="form-control" 
                    name='type' 
                    description="type" 
                    required
                    value="{!! old('type') , isset($training) ? $data->type : "" !!}" placeholder="type">
                    <span class="help-block">
                        <strong>{{ $errors->has('type') ? $errors->first('type') : ''}}</strong>
                        </span>
                </div>
        </div>
           
<legend>Available From / To</legend>

<!-- Date From -->
<div class="form-group{{ $errors->has('from)') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label" for="datefrom">Available From</label>
    <div class="input-group input-group-lg">
    <input class="form-control" 
        type="text" 
        name="datefrom"  
        id="fromdatepicker" 
        value="{{  old('datefrom', isset($training) ? date('m/d/Y',strtotime($training->datefrom)): date('m/d/Y'))}}"/>
    <span class="help-block">
        <strong>{{$errors->has('datefrom') ? $errors->first('datefrom')  : ''}}</strong>
    </span>
    </div>
</div>
<!-- /Date From -->
<!-- Date To -->
<div class="form-group{{ $errors->has('dateto') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label" for="dateto">Available To</label>
    <div class="input-group input-group-lg ">
        <input class="form-control" 
            type="text" 
            name="dateto"  
            id="todatepicker" 
            value="{{  old('dateto', isset($training) ? date('m/d/Y',strtotime($training->dateto)) : date('m/d/Y',strtotime('+1 years'))) }}"/>

        <span class="help-block">
            <strong>{{$errors->has('dateto') ? $errors->first('dateto')  : ''}}</strong>
        </span>
        No Expiration <input type="checkbox" name="noexpiration"  {{isset($training) && $training->datato ? checked : ''}}>
    </div>
</div>
<!-- /Date to -->

<!-- Roles -->
<legend>Roles</legend>
    <div class="form-group{{ $errors->has('roles') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label" for="role">User Roles</label>
        <div class="input-group input-group-lg ">
            @include('training.partials._roles') 
            <span class="help-block{{ $errors->has('roles') ? ' has-error' : '' }}">
                <strong>{{$errors->has('roles') ? $errors->first('roles')  : ''}}</strong>
            </span>
        </div>
    </div>
<!-- / Roles -->