<div class='content'>
    <nav>
       <div class="nav nav-tabs" id="nav-tab" role="tablist">    
           @foreach ($fields->where('depth', 1) as $tab)
                
      
                  <a class="nav-link nav-item @if($loop->first) active @endif" 
                      id="{{$tab->fieldname}}-tab" 
                      data-toggle="tab" 
                      href="#{{$tab->fieldname}}" 
                      role="tab" 
                      aria-controls="{{$tab->fieldname}}" 
                      aria-selected="true">
                    <strong> {{$tab->fieldname}}</strong>
                  </a>
            @endforeach

        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">  
        @foreach ($fields->where('depth', 1) as $tab)
            <div id="{{$tab->fieldname}}" class="tab-pane show @if($loop->first) active @endif" >
                @foreach ($tab->getDescendants() as $field)
                @if($company->salesnotes->where('id', $field->id)->first())
                    @php $fieldvalue = str_replace("<br />", "\r\n", $company->salesnotes->where('id', $field->id)->first()->pivot->fieldvalue); @endphp
                @else
                   @php  $fieldvalue=null; @endphp
                @endif
                <div class="form-group">
                    <label for="{{$field->fieldname}}">{{$field->fieldname}}</label>
                        @if($field->type =='text')
    
                            <input type="text"
                                @if($field->required ==1)
                                required
                                @endif
                                class="form-control"
                                name="{{$field->id}}"
                                value="{!!$fieldvalue!!}"
                                />
                            @elseif($field->type == 'textarea')

                            <textarea name="{{$field->id}}" 
                                class="form-control"
                                @if($field->required ==1)
                                required
                                @endif
                                >{!! $fieldvalue !!}</textarea>
                    
                    
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
                                    name="{{$field->id}}"
                                    value="{{$fieldvalue}}"
                                />
                            @endif 

                    <span class="help-block">
                        <strong>
                            {{ $errors->has($field->id) ? $errors->first($field->id) : ''}}
                        </strong>
                </span>
                </div>
                @endforeach
            </div>
    
        @endforeach 
    </div>
</div>

   