@foreach($fields as $notelet)
    <div class="form-group" id = "{{$field->id}}{{$loop->index}}">
        <label for {{$field->id}}>{{$field->field}}</label>
        @if($field->type =='text')

            <input class="col-md-6"

                type="text"
                @if($field->required ==1)
                required
                @endif
                class="form-control"
                name="{{$field->id}}[]"
               
                />
                
                
            @elseif($field->type == 'textarea')

            <div class= "summernote col-md-6" 
                
                name="{{$field->id}}[]" 
                
                @if($field->required ==1)
                required
                @endif
                >
            </div>
                
    
    
            @elseif ($field->type == 'select')

                @include('salesnotes.partials._select')
            
            @elseif( $field->type == 'radio')
                @include('salesnotes.partials._radio')
            
            @elseif ( $field->type =='checkbox')
                @include('salesnotes.partials._check')
            
            @elseif ($field->type == 'multiselect')
                @include('salesnotes.partials._multiselect')

            @elseif ($field->type == 'file')
                @include('salesnotes.partials._file')

            @elseif ('attachment')
                @include('salesnotes.partials._attachment')
            

            @else
                <input type="text"
                    name="{{$field->id}}[]"

                />
                
            @endif 

        <span class="help-block">
            <strong>
                {{ $errors->has($field->id) ? $errors->first($field->id) : ''}}
            </strong>
        </span>
    
    
    </div>

@endforeach