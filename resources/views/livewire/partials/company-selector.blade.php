<div>
    <legend>Industry / Company</legend>
    <div class="form-group{{ $errors->has('vertical') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label" for="title">Industry Vertical</label>
        <div class="input-group input-group-lg ">
            @foreach($verticals as $descendant)

                @if($descendant->type == 'group'  )
                    @if(! $loop->first)
                        </fieldset>
                    @endif
                    <fieldset>
                                
                    @php $levelName = $descendant->filter; $n=1; @endphp
                     @if(! $loop->first)
                        </li></ul>
                     @endif
                     <ul style="list-style-type: none"> 
                        <li>
                        <input type="checkbox"  id="checkAll" value="{{{$descendant->id}}}">
                        Check All {{{$descendant->filter}}}         
                @else
                    @if(isset($n) && $n > $descendant->depth && ! $loop->first)

                        </li></ul>
                    @elseif(isset($n) and $n < $descendant->depth)
                        <ul style="list-style-type: none">
                    @endif
                    <li>
                    @if((is_array(old('vertical')) && in_array($descendant->id,old('vertical'))) or (isset($activity) && in_array($descendant->id, $activity->vertical->pluck('id')->toArray())))
                        <input type="checkbox"  checked name="vertical[]" value="{{{$descendant->id}}}"/>
                    @else
                        <input type="checkbox"  name="vertical[]" value="{{{$descendant->id}}}"/>
                    @endif
                        
                        {{{trim($descendant->filter)}}}
                    

                @endif
     
    <?php  $n = $descendant->depth;?>

@endforeach
 </li></ul></fieldset>
            
         </div>
         <span class="help-block{{ $errors->has('vertical') ? ' has-error' : '' }}">
                <strong>{{$errors->has('vertical') ? $errors->first('vertical')  : ''}}</strong>
            </span>
     </div> 

<!-- / Industry verticals -->

    <div class="form-group{{ $errors->has('companies') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label" for="companies">Companies</label>
        <div class="input-group input-group-lg ">
            @include('campaigns.partials._companies')
          </div>  
          <span class="help-block{{ $errors->has('vertical') ? ' has-error' : '' }}">
                <strong>{{$errors->has('companies') ? $errors->first('companies')  : ''}}</strong>
            </span>
         
     </div> 
</div>
