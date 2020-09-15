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
                 
                    
                        <p>
                          <strong>{{$field->fieldname}}</strong>
                        </p>
                        @php $notelets = $salesnote->where('howtofield_id', $field->id); @endphp
                         
                             @foreach($notelets as $notelet)
                                <p>{!! $notelet->fieldvalue !!}</p>
                              @endforeach
                        
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>