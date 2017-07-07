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
                <td><a href="{{route('salesactivity.show',$activity->id)}}">
                {{$activity->title}}</a></td>
                <td>{{$activity->datefrom->format('M j, Y') }}</td>
               <td>{{$activity->dateto->format('M j, Y') }}</td>
                <td>
                <ul>
                <?php $filter =array();?>
                @foreach ($activity->vertical as $vertical)
                    @if(! in_array($vertical->filter,$filter))
                    <li>{{$vertical->filter}} </li>
                    <?php $filter[]=$vertical->filter;?>
                    @endif
                @endforeach
                </ul>
                </td>
                 <td>
                <?php $filter =array();?>
                <ul>
                @foreach ($activity->salesprocess as $process)
                    @if(! in_array($process->step,$filter))
                    <li>{{$process->step}} </li>
                    <?php $filter[]=$process->step;?>
                    @endif
                @endforeach
                </ul>
                </td>
                <td>
                    {{count($activity->relatedDocuments())}}
                </td>

                 <td class="col-md-2">


                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu">

                    <li><a href="{{route('salesactivity.edit',$activity->id)}}"><i class="fa fa-pencil" aria-hidden="true"> </i>Edit {{$activity->title}} campaign</a></li>
                     <li><a href="{{route('campaign.announce',$activity->id)}}"><i class="glyphicon glyphicon-envelope"></i> Email campaign team</a></li>
                    <li><a href = "{{route('salesdocuments.index',$activity->id)}}"><i class="glyphicon glyphicon-book"></i> {{$activity->title}} campaign Documents</a></li>
                    <li><a data-href="{{route('salesactivity.destroy',$activity->id)}}" 
                    data-toggle="modal" 
                    data-target="#confirm-delete" 
                    data-title = "location" 
                    href="#"><i class="fa fa-trash-o" aria-hidden="true"> </i> Delete {{$activity->title}} campaign</a>
                    </li>



                    </ul>
                </div>
               
               </td> 


                            
                  

             
                
               
                </tr>  
            
            @endforeach
            </tbody>
            


        </table>
        </div>
                @include('partials._modal')