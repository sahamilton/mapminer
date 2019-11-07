@php
 $files = unserialize(urldecode($field['value']));
@endphp

<input type="hidden" 
    name = "{{$field['id']}}"
    value="{{$field['value']}}"
        />
    @if (is_array($files))
        @foreach ($files as $file)

            @if (file_exists(public_path()."/documents/attachments/".$company->id."/".$file['filename']))
               <li>
                <a href= "{{route('salesnotes.filedelete', $file['filename'])}}">
                    <i class="far fa-trash" aria-hidden="true"></i>

                <a 
                    href ="{{asset("/documents/attachments/".$company->id."/".$file['filename'])}}">
                        {{$file['attachmentname']}}
                </a>
            </li>
            @endif
        @endforeach
        
    @endif

        <fieldset><legend>Add New Attachments</legend>  
        <div class="form-group{{ $errors->has('attachmentname') ? ' has-error' : '' }}">
            <label for="attachmentname">Name:</label>

            <input type="text"
                class='form-control {{ $errors->has('attachmentname') ? ' has-error' : '' }}'
                name="attachmentname" />
        <span class="help-block">
                <strong>{{ $errors->has('attachmentname') ? $errors->first('attachmentname') : ''}}</strong>
                </span>
        </div>

        <div class="form-group" >
            <label for="description">Description:</label>
        <textarea name="attachmentdescription"
            class="form-control"></textarea>
        
        </div>
        <div class="form-group{{ $errors->has('attachment') ? ' has-error' : '' }}">
            <input class ='form-control' type='file' name='attachment' />
            <span class="help-block {{ $errors->has('attachment') ? ' has-error' : ''}}">
                    <strong>{{ $errors->has('attachment') ? $errors->first('attachment') : ''}}</strong>
                    </span>
                
        </div>
    </fieldset>
