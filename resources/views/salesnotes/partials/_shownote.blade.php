<div class='content'>
        <nav>
           <div class="nav nav-tabs" id="nav-tab" role="tablist">   
               @foreach ($fields->where('depth', 1) as $tab)
                    
          
                      <a class="nav-link nav-item @if($loop->first) active @endif" 
                          id="{{$loop->index}}-tab" 
                          data-toggle="tab" 
                          href="#tab{{$loop->index}}" 
                          role="tab" 
                          aria-controls="tab{{$loop->index}}" 
                          aria-selected="true">
                        <strong> {{$tab->fieldname}}</strong>
                      </a>
                @endforeach

            </div>
        </nav>

        <div class="tab-content" id="nav-tabContent"> 

            @foreach ($fields->where('depth', 1) as $tab)
                <div id="tab{{$loop->index}}" 
                  class="tab-pane  @if($loop->first) show active @else fade @endif" >
                  
                    @foreach ($tab->getDescendants() as $field)
                
                    
                        <p>
                          <strong>{{$field->fieldname}}</strong>
                        </p>
                        @php 

                        $notelets = $company->salesnotes->where('id', $field->id); @endphp
                         
                             @foreach($notelets as $notelet)
                              
                                <p>{!! $notelet->pivot->fieldvalue !!}</p>
                              @endforeach
                        
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
