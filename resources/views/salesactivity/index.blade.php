@extends ('site.layouts.default')
@section('content')
<div class="container">
<h2>Sales Campaigns</h2>


<div class="pull-right">
        <a href ="{{route('salesactivity.create')}}"><button class="btn btn-success" >Add Sales Campaign
        </button></a>
    </div>    
   
        
<div class="col-md-10 col-md-offset-1">
        <table class="table" id = "sorttable">
            <thead>

                <th>Title</th>
                <th>From</th>
                <th>To</th>
                <th>Verticals</th>
                <th>Sales Process</th>
                <td>Documents</td>
                <th>Actions</th>
                
            </thead>
            <tbody>
            @foreach ($activities as $activity)

                <tr> 
                <td>{{$activity->title}}</td>
                <td>{{$activity->from->format('Y-m-d') }}</td>
               <td>{{$activity->to->format('Y-m-d') }}</td>
                <td>
                <?php $filter =array();?>
                @foreach ($activity->vertical as $vertical)
                    @if(! in_array($vertical->filter,$filter))
                    <li>{{$vertical->filter}} </li>
                    <?php $filter[]=$vertical->filter;?>
                    @endif
                @endforeach
                </td>
                 <td>
                <?php $filter =array();?>
                @foreach ($activity->salesprocess as $process)
                    @if(! in_array($process->step,$filter))
                    <li>{{$process->step}} </li>
                    <?php $filter[]=$process->step;?>
                    @endif
                @endforeach
                </td>
                <td>
{{count($activity->relatedDocuments())}}
                </td>

                 <td class="col-md-2">
                @include('partials/modal')

                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu">

                    <li><a href="{{route('salesactivity.edit',$activity->id)}}"><i class="glyphicon glyphicon-pencil"></i> Edit {{$activity->title}} campaign</a></li>
                    <li><a href = "{{route('salesdocuments.index',$activity->id)}}"><i class="glyphicon glyphicon-book"></i> {{$activity->title}} campaign Documents</a></li>
                    <li><a data-href="{{route('salesactivity.purge',$activity->id)}}" 
                    data-toggle="modal" 
                    data-target="#confirm-delete" 
                    data-title = "location" 
                    href="#"><i class="glyphicon glyphicon-trash"></i> Delete {{$activity->title}} campaign</a>
                    </li>



                    </ul>
                </div>
               
               </td> 


                            
                  

             
                
               
                </tr>  
            
            @endforeach
            </tbody>
            


        </table>
        </div>
    </div>
</div>
@endsection
