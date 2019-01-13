
<h2>Prospects for direct reports of {{$leads->fullName()}}</h2>

    <div class="row">
        <table id ='sorttable1' class='table table-striped table-bordered table-condensed table-hover'>
            <thead>

                <th>Sales Rep</th>
                <th>Total Prospects</th>
                @foreach($statuses as $key=>$value)<th>{{$value}}</th>
                @endforeach
                <th>Average Rating</th>

            </thead>
            <tbody>

                @foreach ($leads->directReports as $report)
                
               
                <tr>
                    <td><a href="{{route('salesleads.showrep',$report->id)}}">{{$report->postName() }}</a></td>
                    @if($report->salesleads->count()>0)
                        <td>{{$report->salesleads->count()}}</td>
                        <?php $leadstatuses =  $report->salesLeadsByStatus($report->id);?>
                        @foreach($statuses as $key=>$value)
                        <td>
                           
                                 @if (array_key_exists($key+1,$leadstatuses))
                                    {{$leadstatuses[$key+1]['count']}}
                                 @endif 

                        </td>
                        @endforeach
                        <td>
                            @if($report->leadratings->count()>0)
                            {{$report->leadratings->sum('pivot.rating') / ($report->leadratings->count()}}
                            @endif
                        </td>
                    @else
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        
                    @endif
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

