@extends ('admin.layouts.default')
@section('content')
<div class="container">
    <h2>Campaign Documents</h2>
    <p><a href="{{route('salesactivity.index')}}">Return to campaigns</a></p>
    <div class="pull-right">
        <a href ="{{route('salesactivity.create')}}">
            <button class="btn btn-success" ><i class="fa fa-briefcase" aria-hidden="true"> </i> Add Sales Campaign</button>
        </a>
    </div> 
 <table class="table" id = "sorttable">
            <thead>

                <th>Title</th>
                <th>Summary</th>
                <th>Description</th>
                <th>Type</th>
                <th>From</th>
                <th>To</th>
                <th>Verticals</th>
                <th>Sales Process</th>
               
            </thead>
            <tbody>
            @foreach ($documents as $document)

                <tr> 
                <td><a href="{{route('documents.show',$document->id)}}">
                {{$document->title}}</a></td>
                <td>{{$document->summary }}</td>
                <td>{{$document->description }}</td>
                <td>{{$document->doctype}}</td>
               <td>{{$document->datefrom->format('M j, Y') }}</td>
               <td>{{$document->dateto->format('M j, Y') }}</td>
               <td>
               <ul>
               @foreach ($document->vertical as $industry)
                <li>{{$industry->filter}}</li>
               @endforeach
               </ul>
                <td>
               <td>
               <ul>
               @foreach ($document->process as $step)
                <li>{{$step->name}}</li>
               @endforeach
               </ul>
                
                 
               
                </tr>  
            
            @endforeach
            </tbody>
            


        </table>
        </div>
  
@include('partials._scripts')
@endsection
