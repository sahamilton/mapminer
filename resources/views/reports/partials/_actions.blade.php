                <div class="btn-group">
                    <button 
                    type="button" 
                    class="btn btn-success dropdown-toggle" 
                    data-toggle="dropdown">
                        <span class="caret">Actions</span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
            
                        <a class="dropdown-item"
                            href="{{route('reports.edit',$report->id)}}">
                            <i class="far fa-edit text-info"
                             aria-hidden="true"> </i>
                             Edit Report
                        </a>
                        
                        <a class="dropdown-item"
                           data-href="{{route('reports.destroy',$report->id)}}" data-toggle="modal" 
                           data-target="#confirm-delete" 
                           data-title = "{{$report->report}} report" 
                           href="#">
                           <i 
                                class="far fa-trash-alt text-danger" 
                                aria-hidden="true"> 
                            </i> 
                           Delete {{$report->report}} report
                        </a>
                    </ul>
                </div>