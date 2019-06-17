
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
             name='description' 
             title="description">{!! old('description', isset($training) ? $training->description : '') !!}</textarea>
                 <span class="help-block">
                 <strong>{{$errors->has('description') ? $errors->first('description')  : ''}}</strong>
                 </span>
 
         </div>
     </div> 
<!-- reference -->
    <div class="form-group{{ $errors->has('reference') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Reference (URL):</label>
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
 @php
 $types =['fleeq'=>'video','link'=>'link'];
 @endphp
        <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
            <label class="col-md-2 control-label">Type</label>
              <div class="input-group input-group-lg ">
                <select name="type" 
                    id="type"
                    class="form-control">
                    @foreach ($types as $key=>$type)
                        <option 
                        @if (
                        isset($training) && $training->type == $type) 
                        selected 
                        @endif
                        value="{{$key}}">{{$type}}</option>

                    @endforeach
                </select>
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
            value="{{  old('dateto', isset($training) && $training->dateto ? date('m/d/Y',strtotime($training->dateto)) : date('m/d/Y',strtotime('+1 years'))) }}"/>

        <span class="help-block">
            <strong>{{$errors->has('dateto') ? $errors->first('dateto')  : ''}}</strong>
        </span>
        No Expiration <input type="checkbox" name="noexpiration"  {{isset($training) && ! $training->datato ? 'checked' : ''}}>
    </div>
</div>
<!-- /Date to -->

<!-- Roles -->
 
            @include('training.partials._roles') 
            
<!-- / Roles -->
<!-- Industries -->
<legend>Industries</legend>
@include('training.partials._verticals')
<!-- / Industries-->
<div class="form-group{{ $errors->has('serviceline)') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">Servicelines</label>

    <div class="input-group input-group-lg">
        <select multiple class="form-control" name='serviceline[]'>
            @foreach ($servicelines as $key=>$value))
                <option @if(isset($training) && $training->servicelines->contains($key)) selected @endif value="{{$key}}">{{$value}}</option>
            @endforeach
        </select>
        <span class="help-block">
            <strong>{{ $errors->has('serviceline') ? $errors->first('serviceline') : ''}}</strong>
        </span>
    </div>
</div>
