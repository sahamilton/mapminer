<table class='table table-striped' >
    <thead>

        <th>Title</th>
        <th>Description</th>

        <th>Sales Process</th>
        <th>Campaign</th>
        <th>Location</th>
        <th>Rank</th>
        <th>Rated By</th>
        @if(auth()->user()->hasRole('admin'))
            <th>Actions</th>
        @endif
    </thead>
    <tbody >
    @foreach ($documents as $document)

        <tr> 

            <td>
                <a href="{{route('docs.show',$document->id)}}">
                    {{$document->title }}
                </a>
            </td>
            <td>{{$document->description}}</td>
            <td>
                <ul>
                @foreach ($document->process as $process)
                    <li>{{$process->step}}</li>
                @endforeach
                </ul>
            </td>
            <td>
                <ul>
                @foreach ($document->campaigns as $campaign)
                    <li>{{$campaign->title}}</li>
                @endforeach
                </ul>
            </td>

            <td>
                <a href="{{$document->location}}" 
                    target="_new" 
                    title ="View this {{$document->doctype}} document">
                    View Source <img src="{{asset('assets/icons/'.$document->doctype.'.png')}}" >
                </a>
            </td>
            <td> 
                @if($document->rank->count() > 0 
                    && $document->score->count()> 0 
                    && $document->rankings->count() >0)
                        {{$document->rank[0]->rank}}

                @endif
            </td>
            <td>
                @if($document->rankings->count() >0)
                    <a href="{{route('watchedby',$document->id)}}">
                        {{$document->rankings->count()}}
                    </a>
                @else
                    {{$document->rankings->count()}}
                @endif
            </td>
            @if(auth()->user()->hasRole('admin'))
            <td class="col-md-2">

                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu">

                        <a class="dropdown-item"
                             href="{{route('documents.edit',$document->id)}}">
                             <i class="far fa-edit text-info"" aria-hidden="true"> </i>
                                Edit Sales document
                        </a>
                        <a class="dropdown-item" 
                            data-href="{{route('documents.destroy',$document->id)}}" 
                            data-toggle="modal" 
                            data-target="#confirm-delete" 
                            data-title = "document" 
                            href="#">
                            <i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> Delete Sales document
                        </a>
                  </ul>
                </div>
            </td> 
            @endif
            </tr>  

    @endforeach
    </tbody>



</table>   