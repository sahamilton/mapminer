<table class="table" id = "sorttable">
<<<<<<< HEAD
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
                    {{$activity->relatedDocuments()->count()}}
                </td>

                 <td class="col-md-2">


                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu">

                    <li><a href="{{route('salesactivity.edit',$activity->id)}}"><i class="fa fa-pencil" aria-hidden="true"> </i>Edit {{$activity->title}} campaign</a></li>
                     <li><a href="{{route('campaign.announce',$activity->id)}}"><i class="fa fa-envelope" aria-hidden="true"></i> Email campaign team</a></li>
                    <li><a href = "{{route('salesdocuments.index',$activity->id)}}"><i class="fa fa-book" aria-hidden="true"></i> {{$activity->title}} campaign Documents</a></li>
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
=======
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
            {{$activity->relatedDocuments()->count()}}
        </td>

         <td class="col-md-2">
            <div class="btn-group">
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">

                    <a class="dropdown-item"
                        href="{{route('salesactivity.edit',$activity->id)}}">
                        <i class="far fa-edit text-info"" aria-hidden="true"> </i>Edit {{$activity->title}} campaign</a>
                        <a class="dropdown-item"
                         href="{{route('campaign.announce',$activity->id)}}"><i class="far fa-envelope" aria-hidden="true"></i> Email campaign team
                     </a>
                     <a class="dropdown-item"
                        href = "{{route('salesdocuments.index',$activity->id)}}">
                        <i class="far fa-book" aria-hidden="true"></i> {{$activity->title}} campaign Documents
                    </a>
                     <a class="dropdown-item"
                        data-href="{{route('salesactivity.destroy',$activity->id)}}" 
                        data-toggle="modal" 
                        data-target="#confirm-delete" 
                        data-title = "location" 
                        href="#">
                        <i class="far fa-trash-o text-danger" aria-hidden="true"> </i> 
                        Delete {{$activity->title}} campaign

                    </a>    
                 </ul>
            </div>
           </td>
        </tr>  
        @endforeach
        </tbody>
    </table>
</div>
@include('partials._modal')
>>>>>>> development
