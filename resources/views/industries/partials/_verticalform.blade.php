
    <form method="post" name="updateIndustries" method="{{route('industryfocus.store')}}" >
    	@csrf
    

    <div class="form-group{{ $errors->has('vertical') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label" for="title">Industry Focus</label>
        <div class="input-group input-group-lg ">
             @include('industries.partials._verticals')
            <span class="help-block{{ $errors->has('salesprocess') ? ' has-error' : '' }}">
                <strong>{{$errors->has('vertical') ? $errors->first('vertical')  : ''}}</strong>
            </span>
         </div>
     </div>
     <input type="hidden" name="id" value="{{auth()->user()->person->id}}" /> 
     <input type="submit" name="submit" value="Update Industry Focus" class="btn btn-info" />
 </form>
@include('partials._verticalsscript')