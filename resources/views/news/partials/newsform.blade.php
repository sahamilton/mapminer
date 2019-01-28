
<!--- Title -->

    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Title</label>
              <div class="input-group input-group-lg ">
            <input type="text" required 
            class="form-control" 
            name='title' 
            description="title" 
            value="{{ old('title' , isset($news) ? $news->title : "" ) }}" 
            placeholder="title">
            <span class="help-block">
                <strong>{{ $errors->has('title') ? $errors->first('title') : ''}}</strong>
                </span>
        </div>
    </div>

<!-- News -->
	 <div class="form-group{{ $errors->has('news') ? ' has-error' : '' }}">
         <label class="col-md-2 control-label">Article</label>
          <div class="input-group input-group-lg ">
             <textarea class="form-control summernote" 
             name='news' 
             title="news">
             {!! old('news', isset($news) ? $news->news : '') !!}
             </textarea>
                 <span class="help-block">
                 <strong>{{$errors->has('news') ? $errors->first('news')  : ''}}</strong>
                 </span>
 
         </div>
     </div> 

<legend>Available From / To</legend>\

<!-- Date From -->
<div class="form-group{{ $errors->has('from)') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label" for="datefrom">Available From</label>
    <div class="input-group input-group-lg">
    <input class="form-control" 
        type="text" 
        name="datefrom"  
        id="fromdatepicker" 
        value="{{  old('datefrom', isset($news) ? date('m/d/Y',strtotime($news->datefrom)): date('m/d/Y'))}}"/>
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
            value="{{  old('dateto', isset($news) ? date('m/d/Y',strtotime($news->dateto)) : date('m/d/Y',strtotime('+1 years'))) }}"/>

        <span class="help-block">
            <strong>{{$errors->has('dateto') ? $errors->first('dateto')  : ''}}</strong>
        </span>
    </div>
</div>
<!-- /Date to -->
<!-- /Available from to -->

<!--- Service Line -->
		<div class="form-group{{ $errors->has('serviceline)') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Servicelines</label>
               <div class="input-group input-group-lg ">
            <select multiple class="form-control" name='serviceline[]'>
            @foreach ($servicelines as $key=>$serviceline)
                @if((isset($news) && $news->serviceline->contains($key))
                or (is_array(old('serviceline')) && in_array($key,old('serviceline'))))
                or (is_array(old('serviceline')) &&! isset($news)))
            	<option selected value="{{$key}}">{{$serviceline}}</option>
                @else
                <option value="{{$key}}">{{$serviceline}}</option>
                @endif
            @endforeach
            </select>
            <span class="help-block">
                <strong>{{ $errors->has('serviceline') ? $errors->first('serviceline') : ''}}</strong>
                </span>
        </div>
    </div>
<!-- Industry verticals -->

            @include('partials._verticals')  

<!-- / Industry verticals -->
<!-- Roles -->
<legend>Roles</legend>

   @include('news.partials._roles')
<!-- / Sales process steps -->
<input type="hidden" name="user_id" value="{{auth()->user()->id}}" />
