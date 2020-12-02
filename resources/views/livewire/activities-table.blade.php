<div>
    <h2>{{$branch->branchname}}</h2>
    <h4>Activities</h4>

    <table  class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <th>
                <a wire:click.prevent="sortBy('businessname')" role="button" href="#">
                    Company
                    @include('includes._sort-icon', ['field' => 'activity_date'])
                </a>
            </th>
            <th>
                <a wire:click.prevent="sortBy('activity_date')" role="button" href="#">
                    Activity Date
                    @include('includes._sort-icon', ['field' => 'activity_date'])
                </a>
            </th>
            <th>Activity</th>
            <th>Status</th>
            <th>Type</th>
        </thead>
        <tbody>
        @foreach ($activities as $activity)
          
            <tr>
               <td><a href="{{route('address.show', $activity->address_id)}}">{{$activity->businessname}}</a></td> 
               <td>{{$activity->activity_date->format('Y-m-d')}}</td> 
               <td>{{$activity->note}}</td> 
               <td> 
                    {{$activity->completed ==1 ? 'Completed' : 'Planned'}}
                    @if($activity->completed !=1 && $activity->activity_date < now())
                        <i class="fas fa-exclamation-triangle text-danger" title="Overdue activity"></i>
                    @endif
               </td> 
               <td>{{$activity->type->activity}}</td>
            </tr>
        @endforeach
        </tbody>

    </table>
    <div class="row">
            <div class="col">
                {{ $activities->links() }}
            </div>

            <div class="col text-right text-muted">
                Showing {{ $activities->firstItem() }} to {{ $activities->lastItem() }} out of {{ $activities->total() }} results
            </div>
        </div>
    </div>
</div>
