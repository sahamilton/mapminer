<table class='table table-striped table-bordered table-condensed table-hover'>
   <tbody>
    <tr>
    <td>Owner</td>
    @foreach ($statuses as $status)
    @if($status != '')
    <td>{{$status}}</td>
    @endif
    @endforeach
<td>Total</td>
<td>Rating</td>
</tr>

  <?php $grandTotal =0;?>
  @foreach ($projects as $project)
  <?php $total = 0;?>
  @if(isset($project['name']))
    <tr>
    <td><a href="{{route('project.owner',$project['id'])}}">{{$project['name']}}</a></td>
          @foreach ($statuses as $status)
            @if($status != '')
            <td style="text-align: right">
              @if(isset($project['status'][$status]))
                {{$project['status'][$status]}}
                <?php $total = $total + $project['status'][$status];?>
              @endif
            </td>
            @endif
    @endforeach
    <td  style="text-align: right">{{$total}}</td>
    <td  style="text-align: right">{{number_format($project['rating'],1)}}</td>
    <?php $grandTotal = $total + $grandTotal;?>
    </tr>
  @endif
  @endforeach
  <tr>
  <td>Total</td>
   
    @foreach ($statuses as $status)
           
            <td style="text-align: right">
            @if(isset($projects['total']['status'][$status]))
            {{$projects['total']['status'][$status]}}
             @endif
             </td>
           

        
    @endforeach
<td style="text-align: right">{{$grandTotal}}</td>

</tr>
</table>
