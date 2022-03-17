<!-- Campaign Title -->
<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label" for="title">Title</label>
    <div class="input-group input-group-lg col-md-8">
        <input type="text" 
            required 
            class='form-control' 
            name="title" 
            value="{{old('title', isset($campaign) ? $campaign->title :'' )}}" />
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<!-- Description -->

<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label" for="description">Campaign Description</label>
    <div class="input-group input-group-lg col-md-8">
        <textarea 
            required 
            class='form-control' 
            data-error="Please provide some description of this campaign" 
            name="description">{{old('description', isset($campaign) ? $campaign->description :''  )}}</textarea>
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<x-form-select name="campaignmanager_id" label="Campaign Manager:" :options="$campaignmanagers" />

 
<legend>Dates Available</legend>
<!--- Date From -->

<div id="datepicker" class="form-group @if ($errors->has('datefrom')) has-error @endif">
    <label class="control-label col-sm-4" for="datefrom">Date From:</label>
    <div class="input-group date input-group-lg">       
        <input 
            type="text"
            required 
            name='datefrom' 
            class="form-control"  
            value="{{old('datefrom', isset($campaign) ? $campaign->datefrom->format('m/d/Y') : date('m/d/Y'))}}" />
        <span class="input-group-addon">
            <i class="far fa-calendar-alt"></i>
        </span>
    </div> 
    @if ($errors->has('datefrom')) <p class="help-block">{{ $errors->first('datefrom') }}</p> @endif
</div>
<!--- Date To -->

<div id="datepicker1" class="form-group @if ($errors->has('dateto')) has-error @endif">
    <label class="control-label col-sm-4" for="dateto">DateTo:</label>
    <div class="input-group date input-group-lg">

        <input 
            type="text" 
            required 
            name ='dateto' 
            class="form-control" 
             value="{{old('datefrom', isset($campaign) ? $campaign->datefrom->format('m/d/Y') : now()->addWeeks(6)->format('m/d/Y'))}}" /> 
            
        <span class="input-group-addon">
           <i class="far fa-calendar-alt"></i>
        </span>
    </div>   
    @if ($errors->has('dateto')) <p class="help-block">{{ $errors->first('dateto') }}</p> @endif

</div>
<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label" for="type">Campaign Type</label>
    <select required 
    name="type"
    class="form-control"
    >
        <option selected value="open">Open</option>
        <option value="restricted">Restricted</option>
    </select>

         <span class="help-block{{ $errors->has('type') ? ' has-error' : '' }}">
                <strong>{{$errors->has('type') ? $errors->first('type')  : ''}}</strong>
            </span>
</div> 
<div class="form-group{{ $errors->has('companies') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label" for="companies">Companies</label>
    <select 
    multiple 
    required 
    name="companies[]"
    class="form-control"
    >
        @foreach ($companies as $id=>$name)
            <option value="{{$id}}">{{$name}}</option>
        @endforeach
    </select>

         <span class="help-block{{ $errors->has('companies') ? ' has-error' : '' }}">
                <strong>{{$errors->has('companies') ? $errors->first('companies')  : ''}}</strong>
            </span>
</div> 

<!-- Organization Alignment -->

<legend>Organization</legend>
<div class="form-group{{ $errors->has('organization') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label" for="organization">Organization</label>
        <div class="input-group input-group-lg ">
            
            <select name="manager_id" id="manager" class="form-control input-lg">
                <option value="">All Managers</option>
               @foreach($managers as $manager) 
                <option 
                @if (isset($campaign) && $campaign->manager_id == $manager->id) selected @endif
                value="{{$manager->id}}">
                    {{$manager->fullName()}} 
                        (<em>
                            {{$manager->userdetails->roles->first()->display_name}})
                        </em>
                </option>
                @endforeach
            </select>
            <span class="help-block{{ $errors->has('organization') ? ' has-error' : '' }}">
                <strong>{{$errors->has('organization') ? $errors->first('organization')  : ''}}</strong>
            </span>
         </div>
     </div> 
<!-- Service Lines -->
<div class="form-group @if ($errors->has('serviceline')) has-error @endif">
    <label class="col-md-4 control-label" for="roles">Service Lines</label>
    
        <select 
        required
        class="form-control" 
        name="serviceline[]" 
        id="serviceline" 
        multiple
         />

            @foreach ($servicelines as $serviceline)
                
                <option 
                value="{{ $serviceline->id }}"
                
                >
                {{ $serviceline->ServiceLine }}
            </option>
                
            @endforeach
        </select>

        @if ($errors->has('serviceline')) <p class="help-block">{!! $errors->first('serviceline') !!}</p> @endif
    
</div>
<!-- ./ servicelines -->

@include('partials._verticalsscript') 