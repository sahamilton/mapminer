
    <div class="form-group{{ $errors->has('vertical') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label" for="title">Industry Vertical</label>
        <div class="input-group input-group-lg ">
             @include('admin.users.partials._verticals')
            <span class="help-block{{ $errors->has('salesprocess') ? ' has-error' : '' }}">
                <strong>{{$errors->has('vertical') ? $errors->first('vertical')  : ''}}</strong>
            </span>
         </div>
     </div> 
@include('partials._verticalsscript')