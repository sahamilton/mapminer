@extends ('admin.layouts.default')
@section('content')
<div class="container">
<h2>Document Library</h2>
@include('search.search')
<div class="container">
@if(auth()->user()->hasRole('Admin'))
<div class="pull-right">
        <a href ="{{route('documents.create')}}"><button class="btn btn-success" >Add Document
        </button></a>
    </div>    
    
 @endif       
<div class="col-md-10 col-md-offset-1">
        <table class='table table-striped' id='sorttable'>
            <thead>

                <th>Title</th>
                <th>Description</th>
                
                <th>Sales Process</th>
                <th>Vertical</th>

                <th>Location</th>
                <th>Rank</th>
                <th>Rated By</th>
                @if(auth()->user()->hasRole('Admin'))
                <th>Actions</th>
                @endif
            </thead>
            <tbody >
            @foreach ($documents as $document)
              
                <tr> 
                
                <td><a href="{{route('docs.show',$document->id)}}">{{$document->title }}</a></td>
               <td>
                    <span class="teaser">{{substr($document->description,0,100)}}</span>

                    <span class="complete"> {{$document->description}}</span>

                    <span class="more">more...</span>
                </td>
                <td>
                <ul>
                @foreach ($document->process as $process)
                    <li>{{$process->step}}</li>
                    @endforeach
                    </ul>
                </td>
                <td>
                <ul>@foreach ($document->vertical as $vertical)
                    <li>{{$vertical->filter}}</li>
                    @endforeach
                    </ul>
                </td>
                
 
                <td><a href="{{$document->location}}" target="_new" 
                title ="View this {{$document->doctype}} document">View Source <img src="{{asset('assets/icons/'.$document->doctype.'.png')}}" ></a></td>
                <td> 
                 @if(count($document->rank) > 0 && count($document->score)> 0 && count($document->rankings) >0)
                  {{$document->rank[0]->rank}}

            @endif
                  </td>
                  <td>
                  @if(count($document->rankings) >0)
                    <a href="{{route('watchedby',$document->id)}}">
                        {{count($document->rankings)}}
                    </a>
                  @else
                  {{count($document->rankings)}}
                  @endif
                  </td>
                   @if(auth()->user()->hasRole('Admin'))
                 <td class="col-md-2">
                @include('partials/modal')

                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu">

                    <li><a href="{{route('documents.edit',$document->id)}}"><i class="glyphicon glyphicon-pencil"></i> Edit Sales document</a></li>

                    <li><a data-href="{{route('documents.destroy',$document->id)}}" 
                    data-toggle="modal" 
                    data-target="#confirm-delete" 
                    data-title = "location" 
                    href="#"><i class="glyphicon glyphicon-trash"></i> Delete Sales document</a>
                    </li>



                    </ul>

                </div>
               
               </td> 
               @endif

                </tr>  
            
            @endforeach
            </tbody>
            


        </table>
        </div>
    </div>
</div>
@include('partials._search')
@include('partials._scripts')
@endsection
