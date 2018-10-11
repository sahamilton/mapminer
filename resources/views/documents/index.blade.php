@extends ('admin.layouts.default')
@section('content')
<div class="container">
    <h2>Document Library</h2>

    @if(auth()->user()->hasRole('Admin'))
    <div class="pull-right">
        <a href ="{{route('documents.create')}}">
            <button class="btn btn-success" >
            Add Document
            </button>
        </a>
    </div>    

    @endif       
        <div class="col-md-10 col-md-offset-1">    
        @include('search.search')
            <table class='table table-striped' id='sorttable'>
            <thead>

            <th>Title</th>
            <th>Description</th>

            <th>Sales Process</th>

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
            <td>{{$document->description}}
           
            </td>
            <td>
            <ul>
            @foreach ($document->process as $process)
            <li>{{$process->step}}</li>
            @endforeach
            </ul>
            </td>


            <td><a href="{{$document->location}}" target="_new" 
            title ="View this {{$document->doctype}} document">View Source <img src="{{asset('assets/icons/'.$document->doctype.'.png')}}" ></a></td>
            <td> 
            @if($document->rank->count() > 0 && $document->score->count()> 0 && $document->rankings->count() >0)
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
            @if(auth()->user()->hasRole('Admin'))
            <td class="col-md-2">


            <div class="btn-group">
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu" role="menu">

<<<<<<< HEAD
            <li><a href="{{route('documents.edit',$document->id)}}"><i class="fa fa-pencil" aria-hidden="true"> </i>Edit Sales document</a></li>

            <li><a data-href="{{route('documents.destroy',$document->id)}}" 
            data-toggle="modal" 
            data-target="#confirm-delete" 
            data-title = "document" 
            href="#"><i class="fa fa-trash-o" aria-hidden="true"> </i> Delete Sales document</a>
            </li>



            </ul>
=======
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
                <i class="far fa-trash-o text-danger" aria-hidden="true"> </i> Delete Sales document
            </a>
          </ul>
>>>>>>> development

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
@include('partials._modal')
@include('partials._search')
@include('partials._scripts')
@endsection
