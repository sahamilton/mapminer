
<h2>Prospects for direct reports of {{$leads->postName()}}</h2>

    <div class="row">
        <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
            <thead>

                <th>Sales Rep</th>
                <th>Total Prospects</th>
                @foreach($statuses as $key=>$value)
                    @if($value !='Claimed')
                        <th>{{$value}}</th>
                    @endif
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
                        @if($value !='Claimed')
                            <td>
                    
                             @if (array_key_exists($key,$leadstatuses))
                                {{$leadstatuses[$key]['count']}}
                             @endif 

                            </td>
                        @endif
                        @endforeach
                    @else
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

