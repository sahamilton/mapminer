
<h2>Prospects for direct reports of {{$leads->postName()}}</h2>

    <div class="row">
        <table id ='sorttable1' class='table table-striped table-bordered table-condensed table-hover'>
            <thead>

                <th>Sales Rep</th>
                <th>Total Prospects</th>
                @foreach($statuses as $key=>$value)<th>
                    @if($value !='Claimed')
                        {{$value}}
                    @endif
                </th>
                @endforeach
           

            </thead>
            <tbody>

                @foreach ($leads->directReports as $report)
                
               
                <tr>
                    <td><a href="{{route('salesleads.showrep',$report->id)}}">{{$report->postName() }}</a></td>
                    @if(count($report->salesleads)>0)
                        <td>{{count($report->salesleads)}}</td>
                        <?php $leadstatuses =  $report->salesLeadsByStatus($report->id);?>
                        @foreach($statuses as $key=>$value)
                        <td>
                            @if($value !='Claimed')
                                
                        
                                 @if (array_key_exists($key,$leadstatuses))
                                    {{$leadstatuses[$key]['count']}}
                                 @endif 

                                
                            @endif
                        </td>
                        @endforeach
                    @else
                        <td></td>
                        <td></td>
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

