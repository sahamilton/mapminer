@foreach($verticals as $descendant)

    @if($descendant->type == 'group'  )
        @if(! $loop->first)
            </fieldset>
        @endif
        <fieldset>
                    
            @php
            $levelName = $descendant->filter;
             $n=1;
             @endphp
         @if(! $loop->first)
            </li></ul>
         @endif
         <ul style="list-style-type: none"> 
            <li>
            <input type="checkbox" name="parent[]" id="checkAll" value="{{{$descendant->id}}}">
            Check All {{{$descendant->filter}}}         
    @else
        @if(isset($n) && $n > $descendant->depth && !$loop->first)

            </li></ul>
        @elseif(isset($n) and $n < $descendant->depth)
            <ul style="list-style-type: none">
        @endif
        <li>
      

        @if((is_array(old('vertical')) && in_array($descendant->id,old('vertical'))) 
        or (isset($document->verticals) && $document->verticals->contains('id',$descendant->id)))
            <input type="checkbox"  checked name="vertical[]" value="{{{$descendant->id}}}"/>
        @else
            <input type="checkbox"  name="vertical[]" value="{{{$descendant->id}}}"/>
        @endif
            
            {{{trim($descendant->filter)}}}
        

    @endif
     
    @php  $n = $descendant->depth; @endphp

@endforeach
 </li></ul></fieldset>


