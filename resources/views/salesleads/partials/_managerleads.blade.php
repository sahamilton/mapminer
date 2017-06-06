

    <div class="row">
        <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
            <thead>

                <th>Sales Rep</th>
                <th>Total Leads</th>
                @foreach($statuses as $key=>$value)
                    @if($value !='Claimed')
                        <th>{{$value}}</th>
                    @endif
                @endforeach
                

            </thead>
            <tbody>

                @foreach ($leads->directReports as $report)
                <?php $leadstatuses = $report->salesLeadsByStatus($report->id);?>
               
                <tr>
                    <td><a href="{{route('salesleads.showrep',$report->id)}}">{{$report->postName() }}</a></td>
                    <td>{{count($report->salesleads)}}</td>
                    @foreach($statuses as $key=>$value)
                    @if($value !='Claimed')
                        <td>
                       
                         @if (array_key_exists($key,$leadstatuses))
                            {{$leadstatuses[$key]['count']}}
                         @endif 

                        </td>
                    @endif
                    @endforeach
                    
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

