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
            <div id="{{$tab->fieldname}}" 
                class="tab-pane show @if($loop->first) active @endif" >
                @foreach ($tab->getDescendants() as $field)
                
                
                    <strong><label for="{{$field->fieldname}}">{{$field->fieldname}}</label></strong>@foreach($company->salesnotes->where('id', $field->id) as $notelet)
                        <div class="form-group">
                            @php $fieldvalue = str_replace("<br />", "\r\n", $notelet->pivot->fieldvalue); @endphp
                    
                            @if($field->type =='text')
        
                                <input class="col-md-6"
                                    type="text"
                                    @if($field->required ==1)
                                    required
                                    @endif
                                    class="form-control"
                                    name="{{$field->id}}[]"
                                    value="{!!$fieldvalue!!}"
                                    /><input class="col-md-1 btn btn-danger" 
                                    onclick="deleteRow(this.form);" 
                                    type="button" 
                                    value="Delete row" />
                                @elseif($field->type == 'textarea')

                                <textarea 
                                
                                    name="{{$field->id}}[]" 
                                    class="col-md-6"
                                    @if($field->required ==1)
                                    required
                                    @endif
                                    >{!! $fieldvalue !!}</textarea>
                                    <button class="col-md-1 btn btn-danger" 
                                    onclick="deleteRow(this.form);" 
                                   
                                    ty><i class="fas fa-trash-alt"></i></button>
                        
                        
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
                                        value="{!! $fieldvalue !!}"
                                    />
                                    <input onclick="deleteRow(this.form);" 
                                    class="btn btn-danger"
                                    type="button" 
                                    value="Delete row" />
                                @endif 

                            <span class="help-block">
                                <strong>
                                    {{ $errors->has($field->id) ? $errors->first($field->id) : ''}}
                                </strong>
                            </span>
                        </div>

                    @endforeach
                @endforeach

                
            </div>
    
        @endforeach 
    </div>
</div>

   
